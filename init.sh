#!/usr/bin/env bash

docker-compose up -d
docker-compose exec payment_api_php composer install
