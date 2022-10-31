up:
	docker-compose up -d

down:
	docker-compose down

status:
	docker-compose ps

cli:
	docker-compose exec --user=www-data cli bash || true

logs:
	docker-compose logs -f

api_doc:
	redoc-cli bundle docs/api.yaml --output docs/docs.html

format:
	php-cs-fixer fix backend/app

update-db-from-dev:
	 ansible-playbook -i ansible/hosts.yml ansible/update_local_db_from_dev.yml
