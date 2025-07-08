#!/usr/bin/env bash

docker-compose exec payment_api_php  php vendor/bin/codecept run --steps
