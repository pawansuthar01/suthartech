<h2>New SutharTech project inquiry</h2>
<p><strong>Name:</strong> {{ $contact->name }}</p>
<p><strong>Email:</strong> {{ $contact->email }}</p>
<p><strong>Phone:</strong> {{ $contact->phone ?: 'Not provided' }}</p>
<p><strong>Subject:</strong> {{ $contact->subject ?: 'Project inquiry' }}</p>
<p><strong>Message:</strong></p>
<p>{{ $contact->message }}</p>
