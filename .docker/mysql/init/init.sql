# create databases
CREATE DATABASE IF NOT EXISTS `flow_test`;

# create root user and grant rights
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';