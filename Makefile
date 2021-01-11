env ?= local

DOCKER_NAME := paygreen
DOCKERCOMPOSE=docker/docker-compose.$(env).yml
DOCKER_APP = $(DOCKER_NAME)-$(env)

up:
	docker-compose -f $(DOCKERCOMPOSE) -p $(DOCKER_APP) up -d

build:
	docker-compose -f $(DOCKERCOMPOSE) -p $(DOCKER_APP) build

stop:
	docker-compose -f $(DOCKERCOMPOSE) -p $(DOCKER_APP) stop

logs:
	docker-compose -f $(DOCKERCOMPOSE) -p $(DOCKER_APP) logs -f

php:
	docker-compose -f $(DOCKERCOMPOSE) -p $(DOCKER_APP) exec $(DOCKER_NAME)_php bash
