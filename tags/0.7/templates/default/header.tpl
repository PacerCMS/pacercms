{config_load file="pacercms.conf"}<?xml version="1.0" encoding="{#encoding#}" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="{#language#}">
<head>
<title>{$site_name}{if $page_title ne ''} - {$page_title|escape:'html'}{/if}</title>

<meta http-equiv="Content-Type" content="text/html; charset={#encoding#}" />
<meta http-equiv="Pragma" content="no-cache" />
{if $page_description ne ''}
<meta name="description" content="{$page_description|escape:'html'}" />
{else}
<meta name="description" content="{$site_description|escape:'html'}" />
{/if}
{if $article_author ne ''}
<meta name="keywords" content="{$article_keywords|escape:'html'}" />
<meta name="author" content="{$article_author|escape:'html'}" />
<meta name="date" content="{$article_publish|date_format:'%Y-%m-%d %T'}" />
{/if}
<meta name="copyright" content="&copy; {$smarty.now|date_format:'%Y'} {$site_name|escape:'html'}" />
<meta name="generator" content="PacerCMS {$site_cm_version}" />

<link href="{$site_url}/favicon.ico" rel="shortcut icon" type="image/icon" title="Shortcut Icon" />

<link href="{$site_url}/templates/{$site_templates_folder}/{#screen_css#}" rel="stylesheet" type="text/css" media="screen" />
<link href="{$site_url}/templates/{$site_templates_folder}/{#print_css#}" rel="stylesheet" type="text/css" media="print" />

<link href="{$site_url}/feed.php" rel="alternate" type="application/rss+xml" title="{$site_name} - {t}Headlines (RSS){/t}" />

<script src="{$site_url}/includes/functions.js" type="text/javascript"> </script>

</head>
<body id="the-body">

<div id="header">
    <div id="nameplate">
        <h1>{$site_name}</h1>
    </div>
    {if $section_name ne ''}
        {if $section_url ne ''}
            <h2 id="sectionNameplate"><a href="{$section_url}" title="{$section_name|escape:'html'}">{$section_name|escape:'html'}</a></h2>
        {else}
            <h2 id="sectionNameplate">{$section_name|escape:'html'}</h2>
        {/if}
    {/if}
</div>
<div id="pageTop">
    <div id="siteUpdate">
        <p><strong>{t}Last Edition:{/t} </strong><br />
        {$current_issue_date|date_format:"%B %e, %Y"}</p>
    </div>
    <div id="topBar">
        {if $article_publish ne ""}
        <p><strong>{t}Published:{/t}</strong> {$article_publish|date_format:"%B %e, %Y"}
        {if $article_edit > $article_publish}
         <strong>{t}Updated:{/t}</strong> {$article_edit|date_format:"%D %l:%m %p"}
        {/if}
        </p>
        {else}
        <p><strong>{$site_name|escape:'html'} {t}Online Edition{/t}</strong></p>
        {/if}
    </div>
    <div id="searchBox">
        <form action="search.php" method="get">
        <p>
        <label for="s">{t}Search{/t}</label>
        <input type="text" name="s" id="s" class="textField" />
        <input type="submit" name="b" id="b" value="{t}Search{/t}" class="button" />
        <input type="hidden" name="index" id="index" value="article" />
        <input type="hidden" name="sort_by" id="sort_by" value="article_publish" />
        <input type="hidden" name="sort_dir" id="sort_dir" value="DESC" />
        <input type="hidden" name="boolean" id="boolean" value="false" />
        </p>
        </form>
    </div>
</div>

<div id="mainWrap">
{include file="sidebar.tpl"}
