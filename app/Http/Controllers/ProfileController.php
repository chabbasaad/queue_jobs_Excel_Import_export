<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Jobs\ExportUsersJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function export()
    {
      //  ExportUsersJob::dispatch();

        //
        Excel::store(new UsersExport, 'zakaria_imane.xlsx', 'public');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Store the file in a temporary location
        $path = $request->file('file')->store('temp');

        // Read the data from the file
        $data = Excel::toCollection(new UsersImport, $path);

        // Show the data using dd
      // Get the first sheet of the collection
      $rows = $data->first()->toArray();

      // Remove the header row
      $header = array_shift($rows);
        // Array to track processed emails
    $processedEmails = [];

    // Iterate over each row and create a new User
    foreach ($rows as $row) {
        if (is_array($row) && count($row) >= 3) {
            $email = (string) $row[1];

            // Skip if email is already processed
            if (in_array($email, $processedEmails)) {
                continue;
            }

            // Check if the email already exists in the database
            if (User::where('email', $email)->exists()) {
                continue;
            }

            // Create the user and add the email to the processed array
            User::create([
                'name'     => (string) $row[0],  // The first column
                'email'    => $email,  // The second column
                'password' => Hash::make((string) $row[2]),  // The third column
            ]);

            $processedEmails[] = $email;
        }
    }


        // Proceed with the import
       // Excel::import(new UsersImport, $path);

        return redirect('/dashboard')->with('success', 'All good!');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
