git checkout dev
git add .
git commit -F -
git push origin dev
git checkout master
git merge dev
git push origin master
git checkout dev