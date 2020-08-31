# Install mod_ssl 
sudo yum install -y mod_ssl openssh
# Config vhost for server
# Ex: sol_imperialhotel_ec folder is /var/www/sol_imperialhotel_ec
sudo cp /vagrant/apache/conf/sol_imperialhotel-conf.conf /etc/httpd/conf.d/sol_imperialhotel-conf.conf

# Auto start httpd service 
sudo systemctl enable httpd.service
sudo systemctl daemon-reload
# Copy generated ssl certificate
sudo mkdir -p /etc/httpd/ssl/sol_imperialhotel_ec
sudo cp -r /vagrant/apache/ssl/. /etc/httpd/ssl/sol_imperialhotel_ec
sudo systemctl restart httpd.service
