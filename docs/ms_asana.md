classDiagram
direction BT
class asana_users {
   varchar(255) gid
   varchar(255) name
   varchar(255) resource_type
   int id
}
class invoice {
   int payer_id
   int invoice_no
   int act_no
   date date_invoice
   date date_act
   varchar(255) title_invoice
   varchar(255) title_act
   int qty
   decimal(10,2) amount
   timestamp created_at  /* Дата створення */
   timestamp updated_at  /* Дата оновлення */
   int id
}
class migration {
   int apply_time
   varchar(180) version
}
class payer {
   varchar(255) name  /* Назва */
   varchar(255) email  /* Електронна пошта */
   varchar(255) phone  /* Телефон */
   varchar(255) contract  /* Договір */
   varchar(255) director  /* Директор ПІБ */
   varchar(255) director_case  /* ПІБ директора в родовому відмінку */
   text requisites  /* Реквізити */
   datetime created_at  /* Дата створення */
   int id
}
class project {
   varchar(255) name
   bigint gid
   bigint workspace_gid
   varchar(255) resource_type
   int id
}
class project_custom_field_enum_options {
   varchar(255) custom_field_gid
   varchar(255) gid
   varchar(255) name
   varchar(255) color
   tinyint(1) enabled
   varchar(255) resource_type
   int id
}
class project_custom_fields {
   varchar(255) gid
   varchar(255) project_gid
   varchar(255) name
   varchar(255) type
   varchar(255) resource_type
   varchar(255) resource_subtype
   tinyint(1) is_important
   int id
}
class section_project {
   bigint gid
   varchar(255) name
   bigint project_gid
   varchar(255) resource_type
   int id
}
class task {
   varchar(255) gid
   varchar(50) parent_gid
   text name
   varchar(255) assignee_gid
   varchar(255) assignee_name
   varchar(255) assignee_status
   varchar(255) section_project_gid
   varchar(255) section_project_name
   tinyint(1) completed
   datetime completed_at
   datetime created_at
   date due_on
   date start_on
   text notes
   varchar(255) permalink_url
   varchar(255) project_gid
   varchar(255) workspace_gid
   datetime modified_at
   varchar(255) resource_subtype
   int num_hearts
   int num_likes
   text work_done  /* Work Done */
   varchar(255) task_sync  /* Task Sync */
   varchar(255) task_sync_in  /* Task Sync In */
   varchar(255) task_sync_out  /* Task Sync Out */
   int id
}
class task_attachment {
   varchar(255) task_gid
   varchar(255) gid
   datetime created_at
   text download_url
   varchar(255) name
   varchar(255) parent_gid
   varchar(255) parent_name
   varchar(255) parent_resource_type
   varchar(255) parent_resource_subtype
   text permanent_url
   varchar(255) resource_type
   varchar(255) resource_subtype
   text view_url
   int id
}
class task_changes {
   varchar(255) task_gid
   varchar(255) field
   text old_value
   text new_value
   datetime changed_at
   int id
}
class task_custom_fields {
   varchar(255) task_gid
   varchar(255) custom_field_gid
   varchar(255) name
   varchar(255) type
   varchar(255) display_value
   varchar(255) enum_option_gid
   varchar(255) enum_option_name
   decimal(10,2) number_value
   int id
}
class task_story {
   varchar(255) gid
   varchar(255) task_gid
   datetime created_at
   varchar(255) created_by_gid
   varchar(255) created_by_name
   varchar(255) created_by_resource_type
   varchar(255) story_gid
   varchar(255) resource_type
   text text
   varchar(255) resource_subtype
   int id
}
class timer {
   varchar(255) task_gid
   time time
   int minute
   float coefficient
   text comment
   int status
   timestamp created_at
   timestamp updated_at
   int id
}
class user {
   varchar(255) username
   varchar(32) auth_key
   varchar(255) password_hash
   varchar(255) password_reset_token
   varchar(255) email
   smallint status
   int created_at
   int updated_at
   varchar(255) verification_token
   varchar(100) user_asana_gid
   int id
}

invoice  -->  payer : payer_id:id
project_custom_field_enum_options  -->  project_custom_fields : custom_field_gid:gid
section_project  -->  project : project_gid:gid
task_attachment  -->  task : task_gid:gid
task_changes  -->  task : task_gid:gid
task_custom_fields  -->  task : task_gid:gid
task_story  -->  task : task_gid:gid
timer  -->  task : task_gid:gid
