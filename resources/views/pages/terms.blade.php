@extends('components.layout')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-display font-bold text-neutral-900 mb-4">Terms of Service</h1>
            <p class="text-body text-neutral-600">
                Last updated: {{ date('F j, Y') }}
            </p>
        </div>

        <div class="prose prose-lg max-w-none text-neutral-700">
            <h2>Acceptance of Terms</h2>
            <p>
                By accessing and using GiftHub, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
            </p>

            <h2>Use License</h2>
            <p>
                Permission is granted to temporarily use GiftHub for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:
            </p>
            <ul>
                <li>Modify or copy the materials</li>
                <li>Use the materials for any commercial purpose or for any public display</li>
                <li>Attempt to decompile or reverse engineer any software contained on GiftHub</li>
                <li>Remove any copyright or other proprietary notations from the materials</li>
            </ul>

            <h2>User Accounts</h2>
            <p>
                When you create an account with us, you must provide information that is accurate, complete, and current at all times. You are responsible for safeguarding the password and for all activities that occur under your account.
            </p>
            <p>
                You must notify us immediately upon becoming aware of any breach of security or unauthorized use of your account.
            </p>

            <h2>Acceptable Use</h2>
            <p>You agree not to use GiftHub to:</p>
            <ul>
                <li>Violate any applicable laws or regulations</li>
                <li>Infringe on the rights of others</li>
                <li>Transmit harmful or malicious code</li>
                <li>Attempt to gain unauthorized access to our systems</li>
                <li>Harass, abuse, or harm other users</li>
                <li>Post inappropriate, offensive, or illegal content</li>
            </ul>

            <h2>Content Ownership</h2>
            <p>
                The service and its original content, features, and functionality are and will remain the exclusive property of GiftHub and its licensors. The service is protected by copyright, trademark, and other laws.
            </p>

            <h2>User-Generated Content</h2>
            <p>
                By posting content to GiftHub, you grant us a non-exclusive, royalty-free, perpetual, and worldwide license to use, display, and distribute your content in connection with the service.
            </p>
            <p>
                You retain ownership of your content, but you are responsible for ensuring that your content does not violate the rights of others or applicable laws.
            </p>

            <h2>Privacy</h2>
            <p>
                Your privacy is important to us. Please review our <a href="{{ route('pages.privacy') }}" class="text-primary-600 hover:text-primary-700">Privacy Policy</a>, which also governs your use of GiftHub, to understand our practices.
            </p>

            <h2>Termination</h2>
            <p>
                We may terminate or suspend your account and bar access to the service immediately, without prior notice or liability, under our sole discretion, for any reason whatsoever and without limitation, including but not limited to a breach of the Terms.
            </p>
            <p>
                If you wish to terminate your account, you may simply discontinue using the service.
            </p>

            <h2>Disclaimer</h2>
            <p>
                The information on this website is provided on an 'as is' basis. To the fullest extent permitted by law, GiftHub excludes all representations, warranties, conditions and terms whether express or implied, statutory or otherwise.
            </p>

            <h2>Limitations</h2>
            <p>
                In no event shall GiftHub or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on GiftHub, even if GiftHub or a GiftHub authorized representative has been notified orally or in writing of the possibility of such damage.
            </p>

            <h2>Accuracy of Materials</h2>
            <p>
                The materials appearing on GiftHub could include technical, typographical, or photographic errors. GiftHub does not warrant that any of the materials on its website are accurate, complete, or current.
            </p>

            <h2>Modifications</h2>
            <p>
                GiftHub may revise these terms of service at any time without notice. By using this website, you are agreeing to be bound by the then current version of these terms of service.
            </p>

            <h2>Governing Law</h2>
            <p>
                These terms and conditions are governed by and construed in accordance with applicable laws, and any disputes relating to these terms and conditions will be subject to the exclusive jurisdiction of the courts.
            </p>

            <h2>Contact Information</h2>
            <p>
                If you have any questions about these Terms of Service, please contact us at:
            </p>
            <div class="bg-neutral-50 p-4 rounded-lg">
                <p class="font-medium">Email: <a href="mailto:legal@gifthub.test" class="text-primary-600 hover:text-primary-700">legal@gifthub.test</a></p>
                <p class="mt-2">Or use our <a href="{{ route('pages.contact') }}" class="text-primary-600 hover:text-primary-700">contact form</a></p>
            </div>
        </div>
    </div>
</div>
@endsection