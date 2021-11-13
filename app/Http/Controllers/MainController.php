<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class MainController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function create()
    {
        request()->validate(
            [
                'text' => 'string|required|max:20000',
                'encrypt_password' => 'string|min:6|max:100|nullable',
            ],
            [
                'text.required' => 'este campo es obligatorio',
                'text.string' => 'El texto está vacío o se ha producido un error.',
                'text.max' => 'El límite es de 20.000 caracteres.',
                'encrypt_password.string' => 'Disculpe, ocurrió un error.',
                'encrypt_password.min' => 'La contraseña debe contener 6 caracteres como mínimo',
                'encrypt_password.max' => 'La contraseña no puede tener más de 100 caracteres.'
            ]
        );

        $text = Crypt::encryptString(request()->text);

        $slug = Str::slug(Str::random(5));

        if (Note::where('slug', $slug)->get()->isNotEmpty()) {
            $notcomplete = true;
            while ($notcomplete) {
                $slug = Str::slug(Str::random(5));
                if (Note::where('slug', $slug)->get()->isNotEmpty()) {
                    continue;
                } else {
                    $notcomplete = false;
                }
            }
        }

        if (request()->expiration_date !== 'never') {
            if (request()->expiration_date === '1_hour') {
                $expiration_date = date_format(now()->addHours(1), 'Y-m-d H:i:s');
            } elseif (request()->expiration_date === '1_day') {
                $expiration_date = date_format(now()->addDays(1), 'Y-m-d H:i:s');
            } elseif (request()->expiration_date === '1_month') {
                $expiration_date = date_format(now()->addMonths(1), 'Y-m-d H:i:s');
            } elseif (request()->expiration_date === '1_week') {
                $expiration_date = date_format(now()->addWeeks(1), 'Y-m-d H:i:s');
            }
        } else {
            $expiration_date = null;
        }

        if (request()->encrypt_password) {
            $password = Hash::make(request()->encrypt_password);
        } else {
            $password = 'none';
        }

        Note::create([
            'text' => $text,
            'expiration_date' => $expiration_date,
            'password' => $password,
            'slug' => $slug,
        ]);

        $link = route('home') . '/' . 'note/' . $slug;

        return back()->with(['success' => $link]);
    }

    public function show($slug)
    {
        $note = Note::where('slug', $slug)->get();

        if ($note->isEmpty()) {
            return view('password-note');
        } else {
            $note = $note->first();

            if ($note->expiration_date && $note->expiration_date < now()) {
                $note->delete();
                return view('password-note');
            }

            if ($note->password === "none") {
                $password = false;
            } else {
                $password = true;
            }
        }
        return view('password-note', compact('note', 'password'));
    }

    public function decrypt($slug)
    {
        request()->validate([
            'decrypt_password' => 'string|max:100'
        ], [
            'decrypt_password.string' => 'El campo está vacío o se ha producido un error.',
            'decrypt_password.max' => 'La contraseña no puede tener más de 100 caracteres.'
        ]);

        $note = Note::where('slug', $slug)->get();


        if ($note->isEmpty()) {
            return back()->withErrors(['404' => 'Lo siento, esta nota no existe o ya ha sido leída.']);
        } else {
            $note = $note->first();
        }

        if ($note->password !== "none") {
            if (Hash::check(request()->decrypt_password, $note->password)) {
                $note->text = Crypt::decryptString($note->text);
            } else {
                return back()->withErrors(['bad_password' => 'código incorrecto']);
            }
        } else {
            $note->text = Crypt::decryptString($note->text);
        }

        $note->delete();

        return view('show-note', compact('note'));
    }
}