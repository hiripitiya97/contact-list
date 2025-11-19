<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $params = request()->all();
            $search = $params['search'] ?? null;
            $limit = $params['limit'] ?? 10;

            $contacts = Contact::query()
                ->latest('created_at')
                ->when($search, function (Builder $query) use ($search) {
                    $query->where(function (Builder $q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%");
                    });
                })
                ->paginate($limit)
                ->withQueryString()
                ->through(function ($contact) {
                    return [
                        'id' => $contact->id,
                        'name' => $contact->name,
                        'email' => $contact->email,
                        'phone' => $contact->phone,
                        'addedBy' => $contact->added_by,
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Contacts retrieved successfully',
                'contacts' => $contacts
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 422,
            ], 422);
        }
    }


    public function store(ContactRequest $request): JsonResponse
    {
        return $this->saveContact($request);
    }

    public function update(ContactRequest $request, string $id): JsonResponse
    {
        $contact = Contact::query()->where('id', $id)->first();
        if (!$contact) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contact not found',
                'code' => 404,
            ], 404);
        }

        return $this->saveContact($request, $contact);
    }

    private function saveContact(ContactRequest $request, Contact $contact = null): JsonResponse
    {
        try {
            DB::beginTransaction();

            $isNew = false;

            if (!$contact) {
                $contact = new Contact();
                $contact->added_by = Auth::id();
                $isNew = true;
            }

            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;

            $contact->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $isNew ? 'Contact created successfully' : 'Contact updated successfully',
                'contact' => $contact
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 422,
            ], 422);
        }
    }


    public function destroy(string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $contact = Contact::query()->where('id', $id)->first();

            if (!$contact) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contact not found',
                    'code' => 404
                ], 404);
            }

            $contact->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Contact deleted successfully',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 422
            ], 422);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $contact = Contact::find($id);

            if (!$contact) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contact not found',
                    'code' => 404
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Contact retrieved successfully',
                'contact' => [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'phone' => $contact->phone,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 422
            ], 422);
        }
    }

}
