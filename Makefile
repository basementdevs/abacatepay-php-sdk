SRC=src
TESTS=tests

PHP=php
COMPOSER=composer
RECTOR=vendor/bin/rector
PINT=vendor/bin/pint
PEST=vendor/bin/pest

.PHONY: all rector pint test fix clean

all: pint rector test

rector:
	@echo ">> Executando Rector..."
	@$(RECTOR) process $(SRC) --ansi

pint:
	@echo ">> Executando Pint..."
	@$(PINT) --ansi

test:
	@echo ">> Executando Pest..."
	@$(PEST) --colors=always

fix:
	@echo ">> Corrigindo código automaticamente..."
	@$(RECTOR) process $(SRC) --ansi --dry-run=no
	@$(PINT) --ansi
