<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Revolution\Google\Sheets\Facades\Sheets;
class GoogleSpreadsheetController extends Controller
{
    public function syncData()
    {
        $googleSheetData = Sheets::spreadsheet('191x4P2D0WCBI9xrQ1aAHVQxQ8o3QgAFS2oSPVgTeKk8')->sheet('Agent!A1:D500')->get();
        $header = $googleSheetData->pull(0);
        $googleSheetCollection = Sheets::collection($header, $googleSheetData);
        $googleSheetCollection = $googleSheetCollection->take(1000);
        $googleSheetData = $googleSheetCollection->toArray();
        $databaseRecords = DB::table('users')->get();
        // Update or Insert records
        foreach ($googleSheetData as $row) {
            $userData = [
                'id' => $row['id'],
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => Hash::make($row['phone']),
            ];
            $existingData = $databaseRecords->where('id', $userData['id'])->first();
            if ($existingData) {
                // Update existing record
                DB::table('users')->where('id', $userData['id'])->update($userData);
            } else {
                // Insert new record
                DB::table('users')->insert($userData);
            }
        }
        // Delete records not present in the Google Sheet
        $databaseRecordIds = $databaseRecords->pluck('id');
        $googleSheetRecordIds = collect($googleSheetData)->pluck('id');
        $deletedRecordIds = $databaseRecordIds->diff($googleSheetRecordIds);
        if ($deletedRecordIds->isNotEmpty()) {
            DB::table('users')->whereIn('id', $deletedRecordIds)->delete();
        }
       // return view('welcome')->with('success', 'Data imported successfully.');
    }

    public function getprograms()
    {
        $googleSheetData = Sheets::spreadsheet('191x4P2D0WCBI9xrQ1aAHVQxQ8o3QgAFS2oSPVgTeKk8')->sheet('Programs!A1:C500')->get();
        $header = $googleSheetData->pull(0);
        $googleSheetCollection = Sheets::collection($header, $googleSheetData);
        $googleSheetCollection = $googleSheetCollection->take(1000);
        $googleSheetData = $googleSheetCollection->toArray();
        $databaseRecords = DB::table('programs')->get();
        // Update or Insert records
        foreach ($googleSheetData as $row) {
            $programsData = [
                'user_id' => $row['id'],
                'programs' => $row['programs'],
                'date' => $row['date'],
            ];

                // Insert new record
                DB::table('programs')->insert($programsData);

        }
       return view('welcome')->with('success', 'Data imported successfully.');
    }


}


