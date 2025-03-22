<?php

if (!function_exists('generateDocName')) {

    function generateDocName($validated)
    {
        $originalFilename = $validated['document']->getClientOriginalName();
        $extension = $validated['document']->getClientOriginalExtension();

        $originalFilenameWithoutExtension =  pathinfo($originalFilename, PATHINFO_FILENAME);
        $originalFilenameWithoutExtension =  str_replace(' ', '_', $originalFilenameWithoutExtension);
        $originalFilenameWithoutSpecialChar = preg_replace('/[^A-Za-z0-9\-]/', '_', $originalFilenameWithoutExtension);

        $concatenatedNames = '';

        foreach ($validated['signers'] as $signer) {
            $concatenatedNames .= str_replace(' ', '_', $signer['name']) . '_';
        }

        $concatenatedNames = rtrim($concatenatedNames, '_');
        $currentTimestamp = time();

        return "{$originalFilenameWithoutSpecialChar}_{$concatenatedNames}_{$currentTimestamp}.{$extension}";
    }
}

if (!function_exists('generateOtp')) {

    function generateOtp()
    {
        return mt_rand(100000, 999999);
    }
}
