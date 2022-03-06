   
<?php
if(!function_exists('custom_image')){
    function custom_image($request){
        $image = $request->image;
        $name = time().'_' . $image->getClientOriginalName();
        $filePath = $request->file('image')->storeAs('', $name, 'public');
        $instructor['image'] = $name;  
        return $name;
    }
}