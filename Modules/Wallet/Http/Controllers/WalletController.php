<?php

namespace Modules\Wallet\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Modules\Wallet\Models\WalletHistory;
use Currency;
use carbon\Carbon;

class WalletController extends Controller
{
    public function __construct()
    {
        // Page Title
        $this->module_title = 'Wallet';

        // module name
        $this->module_name = 'wallet';

        // directory path of the module
        $this->module_path = 'Wallet::wallet';

        view()->share([
            'module_title' => $this->module_title,
            'module_icon' => 'fa-regular fa-sun',
            'module_name' => $this->module_name,
            'module_path' => $this->module_path,
        ]);
    }

    public function walletHistory($id)
    {
        $module_title = __('messages.wallet_history');
        $module_action = 'List';
        $user_id = $id;

        return view('wallet::wallet_history.index_datatable', compact('module_title', 'module_action', 'user_id'));
    }

    public function walletHistoryData(Datatables $datatable, Request $request)
    {
        $query = WalletHistory::where('user_id', $request->id);

        return $datatable->eloquent($query)
            
            ->editColumn('datetime', function ($data) {
                $timezone = setting('default_time_zone') ?? 'UTC';
                return Carbon::parse($data->datetime)->setTimezone($timezone)->format('Y-m-d H:i:s');
            })
            ->editColumn('activity_type', function ($data) {
                return str_replace("_"," ",ucfirst($data->activity_type));
            })
            
            ->editColumn('amount', function ($data) {
                $wallet = json_decode($data->activity_data); 
                return Currency::format($wallet->credit_debit_amount);
            })
            ->filterColumn('amount', function($query, $keyword) {
                $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(activity_data, '$.credit_debit_amount')) LIKE ?", ["%{$keyword}%"]);
            })
            ->orderColumn('amount', function ($query, $order) {
                $query->orderByRaw("CAST(JSON_UNQUOTE(JSON_EXTRACT(activity_data, '$.credit_debit_amount')) AS DECIMAL(15,2)) {$order}");
            })
            
            
            ->rawColumns(['activity_type','amount'])
            ->orderColumns(['id'], '-:column $1')
            ->make(true);
    }
}
