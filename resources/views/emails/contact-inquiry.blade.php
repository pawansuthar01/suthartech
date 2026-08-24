<h2>New SutharTech project inquiry</h2>

<p><strong>Name:</strong> {{ $inquiry['name'] }}</p>
<p><strong>Email:</strong> {{ $inquiry['email'] }}</p>
<p><strong>Phone:</strong> {{ $inquiry['phone'] ?: 'Not provided' }}</p>
<p><strong>Company:</strong> {{ $inquiry['company'] ?: 'Not provided' }}</p>
<p><strong>Project type:</strong> {{ $inquiry['project_type'] }}</p>
<p><strong>Budget:</strong> {{ $inquiry['budget'] ?: 'Not provided' }}</p>
<p><strong>Message:</strong></p>
<p>{{ $inquiry['message'] }}</p>
