mkdir /var/symfony
chown -R ubuntu:ubuntu /var/symfony
chmod -R 775 /var/symfony
cd /vagrant/symfony
sudo -u ubuntu php bin/console doctrine:migrations:migrate --no-interaction --env=prod

