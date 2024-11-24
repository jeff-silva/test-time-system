#!/bin/sh

[ ! -f "./backend/.env" ] && cp "./backend/.env.example" "./backend/.env"
[ ! -f "./mysql/.env" ] && cp "./mysql/.env.example" "./mysql/.env"
[ ! -f "./phpmyadmin/.env" ] && cp "./phpmyadmin/.env.example" "./phpmyadmin/.env"

docker compose up --build --force-recreate --remove-orphans