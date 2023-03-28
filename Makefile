.PHONY: help FORCE
.SILENT:

.DEFAULT_GOAL = help
BUILD := ".build"
ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))

$(eval $(ARGS):;@:)

help: #Pour générer automatiquement l'aide ## Display all commands available
	$(eval PADDING=$(shell grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk '{ print length($$1)-1 }' | sort -n | tail -n 1))
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-$(PADDING)s\033[0m %s\n", $$1, $$2}'

start-database: ## Start database
	docker stop articles-database || true
	docker run --rm --name articles-database -e POSTGRES_USER=admin -e POSTGRES_PASSWORD=4e6Rg2nFvgqDWuw7sUiG -p 5432:5432 -d postgres || true

start-server: ## Start database
	symfony serve --no-tls

start: start-database start-server

save: 
	git add . && git commit -m "AutoSave" && git push

schema: 
	php bin/console doctrine:database:create --if-not-exists 
	php bin/console doctrine:schema:drop --force
	php bin/console doctrine:schema:update --force --complete

create-users: ## Création d'utilisateurs
	./bin/console app:create-user editor@yopmail.com editor ROLE_EDITOR
	./bin/console app:create-user user@yopmail.com   user   ROLE_USER
	./bin/console app:create-user admin@yopmail.com  admin  ROLE_ADMIN

create-categories:
	./bin/console app:create-category "People" people
	./bin/console app:create-category "Sport" sport

create-articles:
	./bin/console app:create-article page1 "Article Vachement Interessant" editor@yopmail.com people
	./bin/console app:create-article page2 "Article Vachement Interessant" editor@yopmail.com sport

init: schema create-users create-categories create-articles