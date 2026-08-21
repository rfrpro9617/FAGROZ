# Site Fagroz (Faculdade de Agronomia e Zootecnia)

# Docker

Comandos para ver os logs

- docker compose logs
- docker compose logs wordpress
- docker exec mysql-dev sh -c "mysqldump -uroot -prootpassword wordpress" > backup.sql (comando para gerar o backup)
- docker exec -i mysql-dev mysql -u root -prootpassword wordpress < backup.sql (importar backup para o banco)