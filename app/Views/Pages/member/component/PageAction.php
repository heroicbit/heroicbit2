<?php namespace App\Views\Pages\member\component;

use App\Views\Pages\member\PageAction as MemberPageAction;

class PageAction extends MemberPageAction {

    public function supply(){
        $components = [
            ['icon' => 'chatbubble-ellipses-outline', 'label' => "Cheating"],
            ['icon' => 'image-outline', 'label' => "Full Slider"],
            ['icon' => 'lock-closed-outline', 'label' => "Login"],
            ['icon' => 'person-outline', 'label' => "Register"],
            ['icon' => 'refresh-outline', 'label' => "Forgot Password"],
            ['icon' => 'call-outline', 'label' => "SMS Verification"],
            ['icon' => 'person-outline', 'label' => "Profile"],
            ['icon' => 'golf-outline', 'label' => "Product Page"],
            ['icon' => 'cart-outline', 'label' => "Cart"],
            ['icon' => 'receipt-outline', 'label' => "Invoice"],
            ['icon' => 'document-outline', 'label' => "Blank Page"],
            ['icon' => 'help-buoy-outline', 'label' => "FAQ"],
            ['icon' => 'document-text-outline', 'label' => "Blog Post"],
            ['icon' => 'document-text-outline', 'label' => "About"],
            ['icon' => 'call-outline', 'label' => "Contact"],
            ['icon' => 'hammer-outline', 'label' => "Maintenance"],
            ['icon' => 'megaphone-outline', 'label' => "Under Construction"],
        ];

        $title = 'Components';
        $output = compact('title','components');
        return $output;
    }
}