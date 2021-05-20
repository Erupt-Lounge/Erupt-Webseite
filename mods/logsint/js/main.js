/*
@package: Zipp
@version: 0.2 <2019-07-12>
*/

AdminPages.listen( 'logs', async r => {

	const l = r.lang;

	console.log( r.logs[0] );

	r.main = `
<h1>${ r.title }</h1>

<div class="basic-cont">

	<table>
		<thead>
			<tr>
				<td>Cat</td>
				<td>Date</td>
				<td>Uri</td>
				<td>Ip</td>
				<td>Referer</td>
				<td>User Agent</td>
				<td>Custom</td>
			</tr>
		</thead>
		<tbody>
			<tr>
			${ r.logs.map( l => `
				<td title="${ l.mode }">${ esc( l.cat ) }</td>
				<td title="${ l.exTime } ms">${ l.datetime }</td>
				<td title="${ esc( l.host ) }">${ esc( l.uri ) }</td>
				<td>${ l.ip }</td>
				<td>${ esc( l.referer ) }</td>
				<td>${ esc( l.userAgent ) }</td>
				<td>${ esc( l.customLog ) }</td>
				` ).join( '</tr><tr>' ) }
			</tr>
		</tbody>
	</table>

</div>`;

/*

customLog: "user:test"
datetime: "2019-07-16 09:35:50"
exTime: 6.616
host: "onefour.ctrl"
ip: "192.168.95.59"
logId: 407
mode: "debug"
type: "http"
uri: "/_dev/zipp/admin/home/logs/"
userAgent: "Mozilla/5.0 (Windows NT 
*/

	/*r.main = `
<h1>${ r.title }</h1>

<div class="basic-cont">
	
	<table>

		<tr>
			<td>Type</td>
			<td>Date</td>
			<td>Ex Time</td>
			<td>Mode</td>
			<td>Ip</td>
			<td>Host</td>
			<td>URI</td>
			<td>User Agent</td>
			<td>Custom Log</td>
		</tr>

		<tr>
		${ r.logs.map( l => `<td>${ l.join( '</td><td>' ) }</td>` ).join( '</tr><tr>' ) }
		</tr>

	</table>

</div>`;*/

} );

class LogsWidget extends HomeWidget {

	get html() {
		return `<h2 class="ph"></h2>
	<div class="widget-ctn-ph"></div>`;
	}

	async listen() {

		const days = 15;

		const res = await Ajax.json( 'logsint', 'basic', {
			days: days
		} );
		
		if ( !res.ok )
			return alert( res.data );

		const d = res.data;

		console.log( d );

		this.cont.h( `<h2>${ d.title }</h2>
	<div class="logs-widget-cont">
		<p><b>Total:</b> ${ d.total }</p>
		<canvas id="logs-per-day" width="100%"></canvas>
		<h3>Sites</h3>
		<ul>
			<li>${ d.perUri.sort( ( a, b ) => b[1] - a[1] ).map( dd => `<b>${ dd[0] }</b><span>${ dd[1] }</span>` ).join('</li><li>') }</li>
		</ul>
	</div>` );

		const dataPerDay = [];
		const labelsPerDay = [];
		const today = new Date();
		for ( let i = days - 1; i >= 0; i-- ) {
			const date = new Date();
			date.setDate( today.getDate() - i );
			const datetime = new DateTime( date );
			dataPerDay.push( tern( d.perDates, datetime.phpDate, 0 ) );
			labelsPerDay.push( `${ datetime.date }. ${ datetime.niceShortMonth }` );
		}

		console.log( labelsPerDay, dataPerDay );

		const logsPerDay = new Chart( 'logs-per-day', {
			type: 'line',
			data: {
				labels: labelsPerDay,
				datasets: [{
					data: dataPerDay/*,
					borderColor: 'red'*/
				}]
			},
			options: {
				resposive: true,
				legend: {
					display: false
				},
				scales: {
					yAyes: [{
						ticks: {
							beginAtZero: true
						}
					}]
				}
			}
		} );

	}

}
HomeWidgets.add( 'logs', LogsWidget );

/*HomeWidgets.add( 'logs', async () => {

	const res = Ajax.json( 'logsint', 'basic', {
		days: 0
	} );

	return `
<h2>Statistics</h2>
<div class="widget-ctn-ph">Test</div>`;

} );*/


/*
> Range

Nutzer
Nutzer pro Tag (statistik)
Seiten mit Anzahlen
*/