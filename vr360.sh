#!/bin/bash

rm -rf vr360.globalvision.dev

git clone https://github.com/jooservices/vr360.globalvision.dev.git

cd vr360.globalvision.dev

composer clear-cache
composer install

rm -rf krpano
wget https://krpano.com/download/files/krpano-1.19-pr13-linux64.tar.gz
tar -xvf krpano-1.19-pr13-linux64.tar.gz
mv ./krpano-1.19-pr13 ./krpano
./krpano/INSTALL\ -\ Create\ Linux\ Desktop\ Icons.sh 
