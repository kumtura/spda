<?php
namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Berita;

use App\Models\Warta;

use App\Models\Ibadah;

use App\Models\Kategorial;

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


class ApiKategorial extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function get_kategorial_all(Request $request){    	
        $api_kategorial      = Kategorial::get_kategorial();

        $data = [];
        $data["result"] = $api_kategorial;
    	return response()->json($data);   
    }

    public function get_kategorial_by_id(Request $request , $index){    	
        $api_kategorial      = Kategorial::get_kategorial_by_id($index);

    	return response()->json($api_kategorial); 
    }

}
