#!/bin/bash
apt-get update -y && apt-get upgrade -y

apt-get install -y git curl unzip

apt-get install -y docker.io docker-compose
systemctl enable docker
systemctl start docker

docker-compose up -d --build

echo "Provisionamiento completado :D"
