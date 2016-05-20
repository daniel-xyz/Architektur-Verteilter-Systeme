#!/usr/bin/env bash
sudo sh -c 'cd /opt/lampp/htdocs/Architektur-Verteilter-Systeme && git fetch origin master && git reset --hard FETCH_HEAD && git clean -df' &&
cd /opt/lampp/htdocs/Architektur-Verteilter-Systeme/a2/persistence &&
sudo chmod 777 messages.txt &&
sudo chmod 777 iplist.txt &&
sh /opt/lampp/lampp startapache &&
read -p "Server started: http://localhost/Architektur-Verteilter-Systeme/a2/a2.php"