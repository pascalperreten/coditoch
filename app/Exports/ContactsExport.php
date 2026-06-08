<?php

namespace App\Exports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\WithMapping;

class ContactsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public $church_id;
    public $follow_up_person_id;

    public function __construct($church_id, $follow_up_person_id = null)
    {
        $this->church_id = $church_id;
        $this->follow_up_person_id = $follow_up_person_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */

    
    public function query()
    {
        $query = Contact::query()->where('church_id', $this->church_id)->where('foreign_city', false)->where('assigned', true);

        if ($this->follow_up_person_id) {
            $query->where('follow_up_person', $this->follow_up_person_id);
        }
        return $query->with('followUpPerson');
    }

    public function map($contact): array
    {
        return [
            $contact->created_at,
            $contact->name,
            $contact->way_to_get_in_contact,
            $contact->phone,
            $contact->email,
            $contact->social_media,
            $contact->other_contact,
            $contact->city,
            $contact->gender,
            $contact->comments,
            $contact->age,
            $contact->evangelist_name,
            $contact->follow_up_person ? $contact->followUpPerson->first_name . ' ' . $contact->followUpPerson->last_name : null,
            $contact->contacted_date ? $contact->contacted_date->format('Y-m-d H:i:s') : null,
            $contact->meeting_date ? $contact->meeting_date->format('Y-m-d H:i:s') : null,
        ];
    }

    public function headings(): array
    {
        return [
            'Added on',
            'Name',
            'Way to Get in Contact',
            'Phone',
            'Email',
            'Social Media',
            'Other Contact',
            'City',
            'Gender',
            'Comments',
            'Age',
            'Evangelist Name',
            'Follow Up Person',
            'Contacted Date',
            'Meeting Date',
        ];
    }
}
