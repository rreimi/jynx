#bin/sh

echo "Retrieve last changes"
cd /home/mercatino/src/ && /usr/lib/git-core/git pull && cd -
#timestamp=$(date +%s)
#cd /home/mercatino/httpdocs/

echo "Sync last code"
rsync -rtzvin --exclude "vendor/" --exclude "app/storage/" --exclude "boostrap/start.php" --exclude "public/uploads/" --exclude ".htaccess" --exclude "public/packag$

echo "Cleaning cache"
#clear cached views
rm -rf /home/mercatino/httpdocs/app/storage/views/*

echo "Setting permisions"
#set permisions
#chown mercatino:www-data -R /home/mercatino/httpdocs
#chmod 2777 -R /home/mercatino/httpdocs/app/storage
#chmod 2777 -R /home/mercatino/httpdocs/public/uploads
echo "Done"
