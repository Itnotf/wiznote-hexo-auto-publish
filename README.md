[结合 Obsidan + Github Actions + Github Pages 实现自动发布](https://itnotf.github.io/2025/07/24/Obsidian%20+%20Hexo%20+%20GitHub%20Pages%EF%BC%9A%E4%BB%8E%E4%B8%BA%E7%9F%A5%E7%AC%94%E8%AE%B0%E8%87%AA%E5%8A%A8%E5%8F%91%E5%B8%83%E5%88%B0%20Obsidian%20%E9%A9%B1%E5%8A%A8%E7%9A%84%E5%85%A8%E6%B5%81%E7%A8%8B%E8%87%AA%E5%8A%A8%E5%8D%9A%E5%AE%A2%E5%8F%91%E5%B8%83/)

### 配置 Hexo 博客仓库
#### a. 初始化 Hexo 项目

```bash
npm install -g hexo-cli
hexo init .
npm install
```

#### b. 配置Hexo主题

```
# 此步骤省略，需要保证有 source/_posts 目录
```

#### c. 推送到Github仓库

```bash
git init
git remote add origin https://github.com/yourname/hexo-blog.git
git add .
git commit -m "init"
git push -u origin main
```
