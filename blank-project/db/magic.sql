SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `data` #jsonField# NOT NULL,
  `live` enum('Yes','No') NOT NULL DEFAULT 'Yes'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `groups` (`id`, `data`, `live`) VALUES
(1, '{\"group_title\":\"Admin\",\"status\":\"Active\",\"add_access\":[\"bikes\",\"cars\",\"groups\",\"people\",\"users\",\"_settings\"],\"view_access\":[\"bikes\",\"cars\",\"groups\",\"people\",\"users\",\"_settings\"],\"update_access\":[\"bikes\",\"cars\",\"groups\",\"people\",\"users\",\"_settings\"],\"delete_access\":[\"bikes\",\"cars\",\"groups\",\"people\",\"users\",\"_settings\"],\"duplicate_access\":[\"bikes\",\"cars\",\"groups\",\"people\",\"users\",\"_settings\"],\"download_csv_access\":[\"bikes\",\"cars\",\"groups\",\"people\",\"users\",\"_settings\"],\"download_pdf_access\":[\"bikes\",\"cars\",\"groups\",\"people\",\"users\",\"_settings\"],\"redirect\":\"http%3A%2F%2Flocalhost%2Fmagic%2F_admin%2Fmanage.php%2Fgroups%2F%3F\"}', 'Yes');
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `data` #jsonField# NOT NULL,
  `live` enum('Yes','No') NOT NULL DEFAULT 'Yes'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `users` (`id`, `data`, `live`) VALUES
(1, '{\"_____profile\": \"profile\", \"name\": \"Tahir+Ata+Barry\", \"email\": \"tahir%40sulata.com.pk\", \"password\": \"ZEdGb2FYST0=\", \"photo\": \"2018%2F02%2F02%2FBruce-Lee-1973-5a743b38978f3.jpg\", \"theme\": \"red\", \"ip\": \"%3A%3A1222\", \"send_mail_to_user\": \"No\", \"redirect\": \"http%3A%2F%2Flocalhost%2Fmagic%2F_admin%2Fmessage.php%3Fmsg%3DProfile+updated.\", \"status\": \"Active\", \"user_group\": \"Admin\"}', 'Yes');
CREATE TABLE `_logs` (
  `id` int(11) NOT NULL,
  `action_on` date NOT NULL,
  `action_by` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL,
  `mode` varchar(30) NOT NULL,
  `data` #jsonField# NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `_settings` (
  `id` int(11) NOT NULL,
  `data` #jsonField# NOT NULL,
  `live` enum('Yes','No') NOT NULL DEFAULT 'Yes'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `_settings` (`id`, `data`, `live`) VALUES
(1, '{\"title\":\"Site+Name\",\"key\":\"site_name\",\"value\":\"Sulata+iSoft\",\"status\":\"Active\",\"Submit\":\"\"}', 'No'),
(2, '{\"title\":\"dd\",\"key\":\"dd\",\"value\":\"dd\",\"status\":\"Active\",\"Submit\":\"\"}', 'No'),
(3, '{\"setting_title\":\"Site+Name\",\"setting_key\":\"site_name\",\"setting_value\":\"Sulata+iSoft\",\"redirect\":\"http%3A%2F%2Flocalhost%2Fmagic%2F_admin%2Fmanage.php%2F_settings%2F%3F\"}', 'Yes'),
(4, '{\"setting_title\":\"Site+Tagline\",\"setting_key\":\"site_tagline\",\"setting_value\":\"BackOffice\"}', 'Yes'),
(5, '{\"setting_title\":\"Page+Size\",\"setting_key\":\"page_size\",\"setting_value\":\"24\"}', 'Yes'),
(6, '{\"setting_title\":\"Time+Zone\",\"setting_key\":\"time_zone\",\"setting_value\":\"ASIA%2FKARACHI\"}', 'Yes'),
(7, '{\"setting_title\":\"Date+Format\",\"setting_key\":\"date_format\",\"setting_value\":\"mm-dd-yy\"}', 'Yes'),
(8, '{\"setting_title\":\"Allowed+File+Formats\",\"setting_key\":\"allowed_file_formats\",\"setting_value\":\"doc%2Cxls%2Cdocx%2Cxlsx%2Cppt%2Cpptx%2Cpdf%2Cgif%2Cjpg%2Cjpeg%2Cpng\",\"id\":\"8\",\"redirect\":\"http%3A%2F%2Flocalhost%2Fmagic%2F_admin%2Fmanage.php%2F_settings%2F%3F\"}', 'Yes'),
(9, '{\"setting_title\":\"Allowed+Picture+Formats\",\"setting_key\":\"allowed_picture_formats\",\"setting_value\":\"gif%2Cjpg%2Cjpeg%2Cpng\",\"id\":\"9\",\"redirect\":\"http%3A%2F%2Flocalhost%2Fmagic%2F_admin%2Fmanage.php%2Fsettings%2F%3Ff%3DSetting%252BTitle%26sort%3Dasc%26search_field%3D%26s%3D%26q%3D\"}', 'Yes'),
(27, '{\"setting_title\":\"Toggle+Password+%280%2F1%29\",\"setting_key\":\"toggle_password\",\"setting_value\":\"1\",\"redirect\":\"http%3A%2F%2Flocalhost%2Fmagic%2F_admin%2Fmanage.php%2F_settings%2F%3F\"}', 'Yes'),
(11, '{\"setting_title\":\"Site+Email\",\"setting_key\":\"site_email\",\"setting_value\":\"tahir%40sulata.com.pk\"}', 'Yes'),
(12, '{\"setting_title\":\"Site+URL\",\"setting_key\":\"site_url\",\"setting_value\":\"http%3A%2F%2Fwww.sulata.com.pk\"}', 'Yes'),
(13, '{\"setting_title\":\"Employee+Image+Height\",\"setting_key\":\"employee_image_height\",\"setting_value\":\"150\"}', 'Yes'),
(14, '{\"setting_title\":\"Employee+Image+Width\",\"setting_key\":\"employee_image_width\",\"setting_value\":\"100\"}', 'Yes'),
(15, '{\"setting_title\":\"Default+Meta+Title\",\"setting_key\":\"default_meta_title\",\"setting_value\":\"-\"}', 'Yes'),
(16, '{\"setting_title\":\"Default+Meta+Description\",\"setting_key\":\"default_meta_description\",\"setting_value\":\"-\"}', 'Yes'),
(17, '{\"setting_title\":\"Default+Meta+Keywords\",\"setting_key\":\"default_meta_keywords\",\"setting_value\":\"-\"}', 'Yes'),
(18, '{\"setting_title\":\"Default+Theme\",\"setting_key\":\"default_theme\",\"setting_value\":\"default\"}', 'Yes'),
(19, '{\"setting_title\":\"Site+Footer\",\"setting_key\":\"site_footer\",\"setting_value\":\"Developed+by+Sulata+iSoft.\",\"id\":\"19\",\"redirect\":\"http%3A%2F%2Flocalhost%2Fmagic%2F_admin%2Fmanage.php%2Fsettings%2F%3Ff%3DSetting%252BTitle%26sort%3Dasc%26search_field%3D%26s%3D%26q%3D\"}', 'Yes'),
(20, '{\"setting_title\":\"Site+Footer+Link\",\"setting_key\":\"site_footer_link\",\"setting_value\":\"http%3A%2F%2Fwww.sulata.com.pk\"}', 'Yes'),
(21, '{\"setting_title\":\"Show+Modules+in+Sidebar+%280%2F1%29\",\"setting_key\":\"show_modules_in_sidebar\",\"setting_value\":\"1\"}', 'Yes'),
(22, '{\"setting_title\":\"Allow+Multiple+Location+Login+%280%2F1%29\",\"setting_key\":\"allow_multiple_location_login\",\"setting_value\":\"0\",\"redirect\":\"http%3A%2F%2Flocalhost%2Fmagic%2F_admin%2Fmanage.php%2F_settings%2F%3F\"}', 'Yes'),
(23, '{\"setting_title\":\"Site+Currency\",\"setting_key\":\"site_currency\",\"setting_value\":\"Rs.\",\"id\":\"23\",\"redirect\":\"http%3A%2F%2Flocalhost%2Fmagic%2F_admin%2Fmanage.php%2Fsettings%2F%3Ff%3DSetting%252BTitle%26sort%3Dasc%26search_field%3D%26s%3D%26q%3D\"}', 'Yes'),
(24, '{\"setting_title\":\"Default+Column+Width\",\"setting_key\":\"default_column_width\",\"setting_value\":\"6\"}', 'Yes'),
(25, '{\"setting_title\":\"Default+Image+Width\",\"setting_key\":\"default_image_width\",\"setting_value\":\"640\"}', 'Yes'),
(26, '{\"setting_title\":\"Default+Image+Height\",\"setting_key\":\"default_image_height\",\"setting_value\":\"480\"}', 'Yes'),
(28, '{\"setting_title\":\"Magic+Login\",\"setting_key\":\"magic_login\",\"setting_value\":\"magic%40sulata.com.pk\",\"redirect\":\"http%3A%2F%2Flocalhost%2Fmagic%2F_admin%2Fmanage.php%2F_settings%2F%3F\"}', 'Yes'),
(29, '{\"setting_title\":\"Magic+Password\",\"setting_key\":\"magic_password\",\"setting_value\":\"tahir\",\"redirect\":\"http%3A%2F%2Flocalhost%2Fmagic%2F_admin%2Fmanage.php%2F_settings%2F%3F\"}', 'Yes'),
(30, '{\"setting_title\":\"PDF+Format+%28table%2Flist%29\",\"setting_key\":\"pdf_format\",\"setting_value\":\"list\",\"redirect\":\"http%3A%2F%2Flocalhost%2Fmagic%2F_admin%2Fmanage.php%2F_settings%2F%3F\"}', 'Yes'),
(31, '{\"setting_title\":\"Show+Clear+Field+%280%2F1%29\",\"setting_key\":\"show_clear_field\",\"setting_value\":\"1\"}', 'Yes'),
(32, '{\"setting_title\":\"Multi+Delete+%280%2F1%29\",\"setting_key\":\"multi_delete\",\"setting_value\":\"1\",\"redirect\":\"http%3A%2F%2Flocalhost%2Fmagic%2F_admin%2Fmanage.php%2F_settings%2F%3F\"}', 'Yes');
CREATE TABLE `_structure` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `redirect_after_add` enum('Yes','No') NOT NULL DEFAULT 'No',
  `label_add` enum('Yes','No') NOT NULL DEFAULT 'No',
  `label_update` enum('Yes','No') NOT NULL DEFAULT 'No',
  `extrasql_on_add` varchar(255) DEFAULT NULL,
  `extrasql_on_update` varchar(255) DEFAULT NULL,
  `extrasql_on_single_update` varchar(255) DEFAULT NULL,
  `extrasql_on_delete` varchar(255) DEFAULT NULL,
  `extrasql_on_restore` varchar(255) DEFAULT NULL,
  `extrasql_on_view` varchar(255) DEFAULT NULL,
  `structure` text NOT NULL,
  `display` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `save_for_later` enum('Yes','No') NOT NULL,
  `live` enum('Yes','No') NOT NULL DEFAULT 'Yes'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `_structure` (`id`, `title`, `slug`, `redirect_after_add`, `label_add`, `label_update`, `extrasql_on_add`, `extrasql_on_update`, `extrasql_on_single_update`, `extrasql_on_delete`, `extrasql_on_restore`, `extrasql_on_view`, `structure`, `display`, `save_for_later`,`live`) VALUES
(1, 'Users', 'users', 'No', 'No', 'Yes', NULL, NULL, NULL, NULL, NULL, NULL, '[{\"Name\":\"Name\",\"Type\":\"textbox\",\"Length\":\"100\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"Width\":\"6\",\"Show\":\"yes\",\"CssClass\":\"form-control\",\"OrderBy\":\"yes\",\"SearchBy\":\"yes\",\"ExtraSQL\":\"\",\"Source\":\"\",\"Default\":\"\",\"Required\":\"yes\",\"Unique\":\"\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"\",\"Slug\":\"name\"},{\"Name\":\"Email\",\"Type\":\"email\",\"Length\":\"50\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"Width\":\"6\",\"Show\":\"yes\",\"CssClass\":\"form-control\",\"OrderBy\":\"yes\",\"SearchBy\":\"yes\",\"ExtraSQL\":\"\",\"Source\":\"\",\"Default\":\"\",\"Required\":\"yes\",\"Unique\":\"yes\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"\",\"Slug\":\"email\"},{\"Name\":\"Password\",\"Type\":\"password\",\"Length\":\"50\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"Width\":\"6\",\"Show\":\"\",\"CssClass\":\"form-control\",\"OrderBy\":\"\",\"SearchBy\":\"\",\"ExtraSQL\":\"\",\"Source\":\"\",\"Default\":\"\",\"Required\":\"yes\",\"Unique\":\"\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"\",\"Slug\":\"password\"},{\"Name\":\"Photo\",\"Type\":\"picture_field\",\"Length\":\"\",\"ImageWidth\":\"640\",\"ImageHeight\":\"480\",\"Width\":\"6\",\"Show\":\"yes\",\"CssClass\":\"form-control\",\"OrderBy\":\"\",\"SearchBy\":\"\",\"ExtraSQL\":\"\",\"Source\":\"\",\"Default\":\"\",\"Required\":\"\",\"Unique\":\"\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"\",\"Slug\":\"photo\"},{\"Name\":\"Status\",\"Type\":\"dropdown\",\"Length\":\"Active%2CInactive\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"Width\":\"6\",\"Show\":\"yes\",\"CssClass\":\"form-control\",\"OrderBy\":\"\",\"SearchBy\":\"\",\"ExtraSQL\":\"\",\"Source\":\"\",\"Default\":\"Active\",\"Required\":\"yes\",\"Unique\":\"\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"\",\"Slug\":\"status\"},{\"Name\":\"Theme\",\"Type\":\"hidden\",\"Length\":\"\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"Width\":\"1\",\"Show\":\"\",\"CssClass\":\"\",\"OrderBy\":\"\",\"SearchBy\":\"\",\"ExtraSQL\":\"\",\"Source\":\"\",\"Default\":\"Default\",\"Required\":\"\",\"Unique\":\"\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"\",\"Slug\":\"theme\"},{\"Name\":\"IP\",\"Type\":\"ip_address\",\"Length\":\"\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"Width\":\"1\",\"Show\":\"\",\"CssClass\":\"\",\"OrderBy\":\"\",\"SearchBy\":\"\",\"ExtraSQL\":\"\",\"Source\":\"\",\"Default\":\"\",\"Required\":\"\",\"Unique\":\"\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"\",\"Slug\":\"ip\"},{\"Name\":\"User+Group\",\"Type\":\"dropdown_from_db\",\"Length\":\"\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"Width\":\"6\",\"Show\":\"yes\",\"CssClass\":\"form-control\",\"OrderBy\":\"yes\",\"SearchBy\":\"yes\",\"ExtraSQL\":\"AND+json_extract%28data%2C%27%24.status%27%29%3D%27Active%27\",\"Source\":\"groups.Group+Title\",\"Default\":\"\",\"Required\":\"yes\",\"Unique\":\"\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"\",\"Slug\":\"user_group\"},{\"Name\":\"Send+Mail+to+User\",\"Type\":\"radio_button_slider\",\"Length\":\"Yes%2CNo\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"Width\":\"6\",\"Show\":\"\",\"CssClass\":\"form-control\",\"OrderBy\":\"\",\"SearchBy\":\"\",\"ExtraSQL\":\"\",\"Source\":\"\",\"Default\":\"Yes\",\"Required\":\"\",\"Unique\":\"\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"\",\"Slug\":\"send_mail_to_user\"}]', 'Yes', 'No', 'Yes'),
(2, '_Settings', '_settings', 'No', 'Yes', 'Yes', NULL, NULL, NULL, NULL, NULL, NULL, '[{\"Name\":\"Setting+Title\",\"Type\":\"textbox\",\"Length\":\"100\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"Width\":\"12\",\"Show\":\"yes\",\"CssClass\":\"form-control\",\"OrderBy\":\"yes\",\"SearchBy\":\"yes\",\"ExtraSQL\":\"\",\"Source\":\"\",\"Default\":\"\",\"Required\":\"yes\",\"Unique\":\"yes\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"%24%28%27%23setting_key%27%29.val%28doSlugify%28this.value%2C+%27_%27%29%29\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"\",\"Slug\":\"setting_title\"},{\"Name\":\"Setting+Key\",\"Type\":\"textbox\",\"Length\":\"100\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"Width\":\"6\",\"Show\":\"yes\",\"CssClass\":\"form-control\",\"OrderBy\":\"yes\",\"SearchBy\":\"yes\",\"ExtraSQL\":\"\",\"Source\":\"\",\"Default\":\"\",\"Required\":\"yes\",\"Unique\":\"yes\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"yes\",\"Slug\":\"setting_key\"},{\"Name\":\"Setting+Value\",\"Type\":\"textbox\",\"Length\":\"100\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"Width\":\"6\",\"Show\":\"yes\",\"CssClass\":\"form-control\",\"OrderBy\":\"yes\",\"SearchBy\":\"yes\",\"ExtraSQL\":\"\",\"Source\":\"\",\"Default\":\"\",\"Required\":\"yes\",\"Unique\":\"\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"\",\"Slug\":\"setting_value\"}]', 'No', 'No','Yes'),
(3, 'Groups', 'groups', 'No', 'No', 'Yes', NULL, NULL, NULL, NULL, NULL, NULL, '[{\"Name\":\"Group+Title\",\"Type\":\"textbox\",\"Length\":\"20\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"Width\":\"6\",\"Show\":\"yes\",\"CssClass\":\"form-control\",\"OrderBy\":\"yes\",\"SearchBy\":\"yes\",\"ExtraSQL\":\"\",\"Source\":\"\",\"Default\":\"\",\"Required\":\"yes\",\"Unique\":\"yes\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"\",\"Slug\":\"group_title\"},{\"Name\":\"Status\",\"Type\":\"dropdown\",\"Length\":\"Active%2CInactive\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"Width\":\"6\",\"Show\":\"yes\",\"CssClass\":\"form-control\",\"OrderBy\":\"yes\",\"SearchBy\":\"\",\"ExtraSQL\":\"\",\"Source\":\"\",\"Default\":\"Active\",\"Required\":\"yes\",\"Unique\":\"\",\"CompositeUnique\":\"\",\"OnChange\":\"\",\"OnClick\":\"\",\"OnKeyUp\":\"\",\"OnKeyPress\":\"\",\"OnBlur\":\"\",\"ReadOnlyAdd\":\"\",\"ReadOnlyUpdate\":\"\",\"Slug\":\"status\"}]', 'Yes', 'No','Yes');
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `_logs`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `_settings`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `_structure`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`),
  ADD UNIQUE KEY `slug` (`slug`);
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
ALTER TABLE `_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
ALTER TABLE `_structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;