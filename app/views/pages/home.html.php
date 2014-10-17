
<h1>M.A.V.I.S</h1>

<p>This is the simple API for M.A.V.I.S. the Musically Articulated Visual Illustration System.</p>

<h2>Endpoints</h2>

<pre>
	PUT/POST /actions/put
	  key_id=[key ID]
	  type=[on/off] 
	(both params are required)

	GET /actions/get?from=[unix timestamp in seconds]&to=[unix timestamp in seconds] 
	(the `from` param is required)
</pre>