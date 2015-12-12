cp -f /tmp/hexo/*.md /home/web/hexo/source/_posts/
rm -f /tmp/hexo/*.md
cd /home/web/hexo && hexo d -g
