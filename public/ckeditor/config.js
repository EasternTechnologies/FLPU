/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
  // config.uiColor = '#AADC6E';
  
  // Upload images
  config.extraPlugins = 'uploadimage';
  config.filebrowserUploadUrl = '/upload-image';

  // Default font settings
  config.font_defaultLabel = 'Times New Roman';
  config.fontSize_defaultLabel = '15';
  config.fontSize_sizes = '8/8px;9/9px;10/10px;11/11px;12/12px;14/14px;15/15px;16/16px;18/18px;20/20px;' +
    '22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px';
};
