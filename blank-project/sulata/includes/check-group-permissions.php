<?php

//If admin, allow full access
if ($_SESSION[SESSION_PREFIX . 'user_group'] == ADMIN_GROUP_NAME) {
    $addAccess = TRUE;
    $viewAccess = TRUE;
    $previewAccess = TRUE;    
    $editAccess = TRUE;
    $deleteAccess = TRUE;
    $duplicateAccess = TRUE;
    $downloadAccessCSV = TRUE;
    $downloadAccessPDF = TRUE;
} else {
    $groupAccess = suCheckGroupPermissions($table);
    $addAccess = $groupAccess['add_access'];
    $viewAccess = $groupAccess['view_access'];
    $previewAccess = $groupAccess['preview_access'];    
    $editAccess = $groupAccess['update_access'];
    $deleteAccess = $groupAccess['delete_access'];
    $duplicateAccess = $groupAccess['duplicate_access'];
    $downloadAccessCSV = $groupAccess['download_csv_access'];
    $downloadAccessPDF = $groupAccess['download_pdf_access'];
    $addables = $groupAccess['addables'];
    $viewables = $groupAccess['viewables'];
    $previewables = $groupAccess['previewables'];    
    $updateables = $groupAccess['updateables'];
    $deleteables = $groupAccess['deleteables'];
}