git init
git config --local user.name "projekt"
git config --local user.email "projekt@ur.edu.pl"
git add --all
git commit -m "Projekt Komis Samochodowy"
git archive --format=zip HEAD -o ../komis_samochodowy_archive.zip
pause
