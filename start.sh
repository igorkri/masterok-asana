#!/bin/bash

#sudo docker-compose up -d

# останавливаем все контейнеры docker
sudo docker stop $(sudo docker ps -a -q)

sudo systemctl stop apache2 && systemctl stop mysql && docker-compose up -d && xdg-open http://masterok-asana.loc/admin