# wiz2hexo

将为知笔记自动发布到 Hexo 博客的工具，支持：

- 接收 WizNote 推送内容（支持 XML-RPC 协议）
- 将内容转换为 Markdown 格式并写入 `source/_posts`
- 自动生成 Hexo 文章头部信息（YAML Front Matter）
- 自动触发 Hexo 生成与部署（`hexo g -d`）

---

## ✨ 功能特性

- 📝 解析为知笔记 XML 内容，提取标题、日期、标签、正文
- 🧼 清洗 HTML 标签，转换实体字符
- 📌 内容超过 200 字时自动插入 `<!--more-->` 生成摘要
- 🛠️ 输出为 Hexo 标准 `.md` 文件并保存在 `/tmp/hexo`
- 🖥️ 配合 Shell 脚本自动发布到 GitHub Pages

---

## 📦 安装依赖

确保系统安装了 PHP 和 Composer：

```bash
composer install
```

依赖组件：

* symfony/yaml （用于生成 Hexo 的 YAML Front Matter）

---

## 🚀 使用方式

1. 设置为知笔记中的远程发布地址指向此服务
2. 配置 Web 服务器接收 XML-RPC 请求（如 Nginx + PHP-FPM）
3. 每次发布会在 `/tmp/hexo/` 生成 `.md` 与 `.xml` 文件
4. 使用如下脚本完成自动发布：

```bash
# auto_publish.sh
cp -f /tmp/hexo/*.md /home/web/hexo/source/_posts/
rm -f /tmp/hexo/*.md
cd /home/web/hexo && hexo d -g
```

---

## 📂 Hexo 配置建议（部分）

\_config.yml 中的关键设置：

```yaml
source_dir: source
default_layout: post
theme: yilia
deploy:
  type: git
  repo: git@github.com:Itnotf/itnotf.github.io.git
  branch: master
```

---

## 🧪 示例

示例 POST XML → 自动生成 Markdown：

```md
---
title: 示例笔记
date: 2025-07-24 10:00:00
updated: 2025-07-24 10:00:00
category: 日志
tags:
  - 示例
  - 笔记
---
这是笔记内容开头……
<!--more-->
这是笔记内容的剩余部分。
```
