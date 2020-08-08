# word
export LC_ALL="en_US.UTF-8"
timedatectl set-timezone Europe/Warsaw
locale-gen en_US.UTF-8
apt update && apt upgrade -y && apt dist-upgrade -y && apt autoremove -y
apt install php php7.3-zip php7.3-xml composer mc libreoffice git -y
cd /var/www/
git clone https://github.com/robsonek/word.git
rm -R /var/www/html/
mv /var/www/word/ /var/www/html/
mkdir /var/www/html/tmp
chmod 777 /var/www/html/tmp
cd /var/www/html/
composer update

