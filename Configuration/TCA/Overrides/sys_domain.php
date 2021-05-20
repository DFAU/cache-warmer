<?php

// v10change TODO: fully drop, this is only here to have a reference for the rewrite

$tempColumns = [
    // Column name should be usually lower underscore cased. But sys_domain fields are already lower camel cased, so we integrate.
  'sitemapFileName' => [
      'label' => 'Sitemap File Name',
      'exclude' => 1,
      'config' => [
          'type' => 'input',
          'default' => 'sitemap.xml',
      ],
  ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_domain', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_domain', 'sitemapFileName');
