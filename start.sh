#!/bin/bash

#sudo docker-compose up -d
sudo systemctl stop apache2 && systemctl stop mysql && docker-compose up -d && xdg-open http://masterok-asana.loc/admin