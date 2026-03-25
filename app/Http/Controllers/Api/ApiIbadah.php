<?php
namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Berita;

use App\Models\Warta;

use App\Models\Ibadah;

use DB;
use File;
use Carbon;
use View;
use Blade;

use Session;

use App\Helper\Helper;

use App\Mail\EmailSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

use Intervention\Image\ImageManagerStatic as Image;

class ApiIbadah extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function get_ibadah_all(Request $request){    	
        $api_ibadah      = Ibadah::get_ibadah();
        
        $data = [];
        $data["result"] = $api_ibadah;
    	return response()->json($data);  
    }

    public function get_ibadah_by_id(Request $request , $index){    	
        $api_ibadah      = Ibadah::get_ibadah_by_id($index);

    	return response()->json($api_ibadah); 
    }

}
