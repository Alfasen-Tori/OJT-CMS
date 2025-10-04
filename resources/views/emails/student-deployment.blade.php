@component('mail::message')
# Internship Endorsement Confirmation

Dear {{ $studentName }},

Congratulations! You have been **endorsed and deployed** for your internship at **{{ $hteName }}**.

## What You Need to Do Next:
1. **Download and Review Attachments**:
   - **Student Internship Contract**: This is your personalized contract. Print it, sign it, and submit the physical copy to your coordinator.
   - **Endorsement Letter**: Official letter confirming your deployment (for your records).

2. **Submission Process**:
   - Bring the signed contract to your coordinator ({{ auth()->user()->fname }} {{ auth()->user()->lname }}).
   - Your coordinator will handle obtaining signatures from the HTE representative and administration, followed by notarization.
   - This process is outside the system—track progress with your coordinator.

3. **Important Notes**:
   - Ensure all details in the contract are correct before signing.
   - Deployment is now active—prepare for your internship start date.
   - If you have questions, contact your coordinator at {{ auth()->user()->email }}.

Thank you for your participation!

Best regards,  
**Internship Coordination Office**  
{{ config('app.name') }}  

@component('mail::button', ['url' => route('intern.dashboard')])  {{-- Adjust route as needed --}}
View Your Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent