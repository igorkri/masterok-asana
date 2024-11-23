<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $gid
 * @property string $name
 * @property string|null $assignee_gid
 * @property string|null $assignee_name
 * @property string|null $assignee_status
 * @property int|null $completed
 * @property string|null $completed_at
 * @property string|null $section_project_gid
 * @property string|null $section_project_name
 * @property string $created_at
 * @property string|null $due_on
 * @property string|null $start_on
 * @property string|null $notes
 * @property string|null $permalink_url
 * @property string|null $project_gid
 * @property string $workspace_gid
 * @property string|null $modified_at
 * @property string|null $resource_subtype
 * @property int|null $num_hearts
 * @property int|null $num_likes
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gid', 'name', 'created_at', 'workspace_gid'], 'required'],
            [['completed', 'num_hearts', 'num_likes'], 'integer'],
            [['completed_at', 'created_at', 'due_on', 'start_on', 'modified_at'], 'safe'],
            [['name', 'notes'], 'string'],
            [['section_project_name', 'section_project_gid', 'gid', 'assignee_gid', 'assignee_name', 'assignee_status', 'permalink_url', 'project_gid', 'workspace_gid', 'resource_subtype'], 'string', 'max' => 255],
            [['gid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => 'ID завдання (GID)',
            'section_project_gid' => 'ID проєкту секції',
            'section_project_name' => 'Назва проєкту секції',
            'name' => 'Назва завдання',
            'assignee_gid' => 'ID виконавця',
            'assignee_name' => 'Ім\'я виконавця',
            'assignee_status' => 'Статус виконавця',
            'completed' => 'Завершено',
            'completed_at' => 'Дата завершення',
            'created_at' => 'Дата створення',
            'due_on' => 'Кінцева дата завдання',
            'start_on' => 'Дата початку завдання',
            'notes' => 'Примітки',
            'permalink_url' => 'Посилання на завдання',
            'project_gid' => 'ID проєкту',
            'workspace_gid' => 'ID робочого простору',
            'modified_at' => 'Дата останньої зміни',
            'resource_subtype' => 'Підтип завдання',
            'num_hearts' => 'Кількість сердечок',
            'num_likes' => 'Кількість лайків',
        ];
    }


    public static function attribute($attribute)
    {
        $labels = (new Task)->attributeLabels();
        return $labels[$attribute] ?? $attribute;
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, ['gid' => 'project_gid']);
    }

    public function getWorkspace()
    {
        return $this->hasOne(Workspace::class, ['gid' => 'workspace_gid']);
    }

    public function getAssignee()
    {
        return $this->hasOne(AsanaUsers::class, ['gid' => 'assignee_gid']);
    }

    public function getSectionProject()
    {
        return $this->hasOne(Project::class, ['gid' => 'section_project_gid']);
    }

    public function getCustomFields()
    {
        return $this->hasMany(TaskCustomFields::class, ['task_gid' => 'gid']);
    }

    public function getStatus()
    {
        return $this->section_project_name;
    }

    public function getStatusList()
    {
        $sections = SectionProject::find()->where(['project_gid' => $this->project_gid])->all();
        return ArrayHelper::map($sections, 'gid', 'name');
    }

    public function getStatusColor()
    {
        if ($this->section_project_name == 'До роботи') {
            return 'success';
        } elseif ($this->section_project_name == 'В роботі') {
            return 'warning';
        } elseif ($this->section_project_name == 'Завершено') {
            return 'danger';
        } elseif ($this->section_project_name == 'Потребує уточнення') {
            return 'primary';
        } else {
            return 'info';
        }
    }

    /**
     * Get priority color
     *
     * @return string
     */
    public function getPriorityColor($priority): string
    {
        if ($priority == 'Високий') {
            return 'danger';
        } elseif ($priority == 'Призупинена') {
            return 'warning';
        } elseif ($priority == 'Низький') {
            return 'success';
        } else {
            return 'info';
        }
    }

    public function getTypeTask($type)
    {
        /**
         * Помилка
         * Покращення
         * Обслуговування
         * Нова функція
         */
        if ($type == 'Помилка') {
            return 'danger';
        } elseif ($type == 'Покращення') {
            return 'warning';
        } elseif ($type == 'Обслуговування') {
            return 'success';
        } elseif ($type == 'Нова функція') {
            return 'info';
        } else {
            return 'primary';
        }
    }

    public function getType()
    {
        foreach ($this->customFields as $customField) {
            if ($customField->name == 'Тип задачі') {
                $value = '<li class="sa-meta__item">
                                    <div title="Тип задачі" class="badge badge-sa-' . $this->getTypeTask($customField->display_value). '">' . $customField->display_value . '</div>
                                </li>';
                return $value;
            }
        }
        return '';

    }

    public function getType2()
    {
        foreach ($this->customFields as $customField) {
            if ($customField->name == 'Тип задачі') {
                $value = [
                    'name' => $customField->display_value,
                    'color' => $this->getTypeTask($customField->display_value)
                    ];
                return $value;
            }
        }
        return '';

    }


    public function getTypeList($priority_gid = null)
    {
        $priorities = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1205860710071790']) //'Тип задачі'
            ->orderBy('display_value ASC')
            ->distinct()->all();
        $list = [];
        foreach ($priorities as $priority) {
            $list[$priority->enum_option_gid] = $priority->display_value;
        }
        return $priority_gid ? $list[$priority_gid] : $list;
    }

    /**
     * Список Час, план.
     */
    public function getTimePlan()
    {
        $time = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1202687202895300']) //'Час, план.'
            ->andWhere(['task_gid' => $this->gid])
            ->one();

        return $time && $time->display_value > 0 ? $time->display_value : 0;
    }

    /**
     * Список Час, факт.
     */
    public function getTimeFact()
    {
        $time = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1202687202895302']) //'Час, факт.'
            ->andWhere(['task_gid' => $this->gid])
            ->one();
        return $time && $time->display_value > 0 ? $time->display_value : 0;
    }

    /**
     * Оплата (замовник)
     */
    public function getPaymentCustomer()
    {
        $payment = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1208141652894034']) //'Оплата (замовник)'
            ->andWhere(['task_gid' => $this->gid])
            ->one();
        return $payment && $payment->display_value != null ? $payment->display_value : '—';
    }

    /**
     * Оплата (фахівець)
     */
    public function getPaymentSpecialist()
    {
        $payment = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1208291886801418']) //'Оплата (фахівець)'
            ->andWhere(['task_gid' => $this->gid])
            ->one();
        return $payment && $payment->display_value != null ? $payment->display_value : '—';
    }

    /**
     * Список Час, рахунок.
     */
    public function getTimeBill()
    {
        $time = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1205860798526183']) //'Час, рахунок.'
            ->andWhere(['task_gid' => $this->gid])
            ->one();
        return $time && $time->display_value > 0 ? $time->display_value : 0;
    }

    /**
     * Get priority
     *
     * @return string
     */
    public function getPriority()
    {
        foreach ($this->customFields as $customField) {
//            debugDie($customField);
            if ($customField->name == 'Приоритет') {
                $value = '<li class="sa-meta__item">
                                    <div title="Приоритет" class="badge badge-sa-' . $this->getPriorityColor($customField->display_value). '">' . $customField->display_value . '</div>
                                </li>';
                return $value;
            }
        }
        return '';
    }

    public function getPriority2()
    {
        foreach ($this->customFields as $customField) {
//            debugDie($customField);
            if ($customField->name == 'Приоритет') {
                $value = [
                    'name' => $customField->display_value,
                    'color' => $this->getPriorityColor($customField->display_value)
                ];
                return $value;
            }
        }
        return '';
    }



    public function getNameGrid()
    {
        $name = '<div class="d-flex align-items-center">
                    <div>
                        <a href="'.Url::to(['update', 'gid' => $this->gid]).'" class="text-reset">' . $this->name . '</a>
                        <div class="sa-meta mt-0">
                            <ul class="sa-meta__list">
                                <li class="sa-meta__item">
                                    <span title="Дата створення '.$this->created_at.'" class="st-copy">' . Yii::$app->formatter->asDate($this->created_at, 'medium') . '</span>
                                </li>
                                <li class="sa-meta__item">
                                    <span title="Дата оновлення '.$this->modified_at.'" class="st-copy">' . Yii::$app->formatter->asDate($this->modified_at, 'medium') . '</span>
                                </li>
                                <li class="sa-meta__item">
                                    <span title="Виконавець" class="st-copy">' . $this->assignee_name . '
                                   </span>
                                </li>
                                '.$this->getPriority().' ' . $this->getType() . '
                            </ul>
                        </div>
                    </div>
                </div>';
        $name_other = '<div class="d-flex align-items-center">
                    <div>
<hr style="margin: 2px auto">
                        <div class="sa-meta mt-0">
                            <ul class="sa-meta__list">
                                <li class="sa-meta__item">
                                    <span title="Дата початку задачі" class="st-copy">Дата початку '
            . ($this->start_on ? Yii::$app->formatter->asDate($this->start_on, 'medium') : '-') .
            '</span>
                                </li>
                                <li class="sa-meta__item">
                                    <span title="Дата закінчення задачі" class="st-copy">Дата закінчення '
            . ($this->due_on ? Yii::$app->formatter->asDate($this->due_on, 'medium') : '-') .
            '</span>
                                </li>
                                <li class="sa-meta__item">
                                    <span title="Пріорітет" class="st-copy">Високий</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>';

        return $name;
    }

    public function getDueOn()
    {
        return $this->due_on ? Yii::$app->formatter->asDate($this->due_on, 'php:d.m.Y') : '';
    }

    public function getStartOn()
    {
        return $this->start_on ? Yii::$app->formatter->asDate($this->start_on, 'php:d.m.Y') : '';
    }

    public function getRealTime()
    {
        $time = TaskCustomFields::find()
            ->where(['task_gid' => $this->gid])
            ->andWhere(['name' => 'Час, факт.'])
            ->orderBy(['id' => SORT_DESC])->one();

        return $time ? $time->display_value : 0;

    }

    /**
     * Користувачі в проєкті
     */
    public function getAssigneeList($assignee_gid = null)
    {
        $assignees = Task::find()->select(['assignee_gid', 'assignee_name'])->where(['project_gid' => $this->project_gid])->distinct()->all();
        $list = [];
        foreach ($assignees as $assignee) {
            if ($assignee->assignee_name && $assignee->assignee_name != 'Private User') {
                $list[$assignee->assignee_gid] = $assignee->assignee_name;
            }
        }
        return $assignee_gid ? $list[$assignee_gid] : $list;
    }

    /**
     * Приоритети задач
     */
    public function getPriorityList($priority_gid = null)
    {
        $priorities = TaskCustomFields::find()
            ->select(['enum_option_gid', 'display_value'])
            ->where(['custom_field_gid' => '1202674799521449']) //'Приоритет'
//            ->where(['name' => 'Приоритет'])
//            ->andWhere(['task_gid' => $this->gid])
            ->orderBy('display_value ASC')
            ->distinct()->all();
        $list = [];
        foreach ($priorities as $priority) {
            $list[$priority->enum_option_gid] = $priority->display_value;
        }
        return $priority_gid ? $list[$priority_gid] : $list;
    }
}
