
# WizNote to Hexo Auto Publish

## 项目简介

本项目提供一个基于 PHP 的脚本，将为知笔记（Wiz Note）发布的内容自动转换为 Hexo 博客支持的 Markdown 格式文件，保存到 Hexo `source` 文件夹下，实现博客内容的自动更新和发布。

## 功能特点

- 自动解析为知笔记发布的 XML 内容
- 清理 HTML 标签，转换成纯文本 Markdown 格式
- 自动生成 Hexo 兼容的 YAML 头部（标题、日期、分类、标签）
- 支持文章摘要分割（`<!--more-->`）
- 自动保存对应的 XML 备份文件
- 便于结合 Hexo 生成静态博客，保持笔记和博客同步

## 使用说明

1. 配置脚本运行环境，确保安装 PHP 和 Composer 依赖（Symfony YAML 组件）：

   ```bash
   composer install
   ```

2. 配置脚本中的路径和权限，确保临时目录存在且可写。

3. 将为知笔记的发布请求指向该脚本。

4. Hexo 执行博客生成命令即可看到最新内容。


## 依赖

* PHP 7+
* Composer
* symfony/yaml 组件

## 适用场景

* 需要将为知笔记中的内容无缝同步到 Hexo 博客的用户
* 希望自动化博客发布流程，减少手动转换的开发者

## 许可证

MIT License

---

欢迎 Star 和 Fork，贡献建议和改进均欢迎提交 Issue 或 Pull Request！
