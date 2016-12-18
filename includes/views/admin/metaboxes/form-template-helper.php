<div class="wp-mailman-form-manager helper-block container-fluid">
	<div class="row">
		<p class="twelve columns"><strong>Getting Started</strong> <span class="text-muted">| <em>What a Form Template is</em></span></p>
		<div class="ten columns offset-by-one">
			<p>A <strong>Form Template</strong> is a <code>blueprint</code> of how a <u>Form</u> will be structured and presented. It uses both native <em>HTML</em> and specific markups unique to <strong>Mailman Form Manager&trade;</strong>.</p>
		</div>

		<p class="twelve columns"><strong>Usage</strong> <span class="text-muted">| <em>How to Write a Form Template</em></span></p>
		<div class="ten columns offset-by-one">
			<p>Basically, you write a <strong>Form Template</strong> just like writing a post. You can even have shortcodes and everything will be parsed accordingly.</p>

			<p>Here's an example to illustrate how to write with <strong>Form Template</strong>.</p>
<pre class="code-box">
<h1>Fill up the form.</h1><p>This is a paragraph explaining something.</p>
[loop]
	[div class="col-sm-3"]
		%label%
	[/div]
	[div class="col-sm-9"]
		[div class="form-group"]
			%field%
			%error%
		[/div]
	[/div]
[/loop]
[div class="row"]
	[div class="col-sm-12"]
		%submit-button%
	[/div]
[/div]
</pre>
			<p>In the above snippet, We see a heading, a paragraph, some shortcodes and <code>keywords</code> wrapped in <code>%</code>.</p>
			<p>The shortcode <code>[loop]</code> loops through all the <u>Fields</u> in a <u>Form</u>, repeating whatever content is inside the <code>[loop]</code> for every <u>field</u>.</p>
			<p>Through the <code>keywords</code>, the template can replace all instances of it's counterpart content. So for the keyword <code>%label%</code>, it is replaced by the <u>Label</u> of the field. Same goes for all keywords.</p>

			<h4>Default <code>Keywords</code>:</h4>
			<p>Keywords that only work inside the shortcode <code>[loop]</code>:</p>
			<ul class="list">
				<li><strong>%label%</strong> - The label of the field.</li>
				<li><strong>%field%</strong> - The input tag of the field.</li>
				<li><strong>%error%</strong> - The tag to put the error message(s) of the field.</li>
			</ul>
			<p>Other Keywords include:</p>
			<ul class="list">
				<li><strong>%errors%</strong> - The tag to put all the error messages of the form.</li>
				<li><strong>%submit-button%</strong> - The Submit Button.</li>
				<li><strong>%reset-button%</strong> - The Reset Button.</li>
				<li><strong>%link-button%</strong> - The Link Button.</li>
			</ul>
		</div>
	</div>
</div>