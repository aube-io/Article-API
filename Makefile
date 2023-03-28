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
	symfony serve

start: start-database start-server

save: 
	