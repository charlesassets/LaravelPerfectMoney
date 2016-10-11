<?php
/**
 *
 * @author Charles Patterson <charlesassets@gmail.com>
 *
 */
?>
<form action="https://perfectmoney.is/api/step1.asp" method="POST">
    <input type="hidden" name="PAYEE_ACCOUNT" value="{{ ( isset( $data['PAYEE_ACCOUNT'] ) ? $data['PAYEE_ACCOUNT'] : config('perfectmoney.marchant_id')) }}">
    <input type="hidden" name="PAYEE_NAME" value="{{ ( isset( $data['PAYEE_NAME'] ) ? $data['PAYEE_NAME'] : config('perfectmoney.marchant_name') ) }}">
    <input type="text" name="PAYMENT_AMOUNT" value="{{ ( isset( $data['PAYMENT_AMOUNT'] ) ? $data['PAYMENT_AMOUNT'] : '' ) }}" placeholder="Amount">
    <input type="hidden" name="PAYMENT_UNITS" value="{{ ( isset( $data['PAYMENT_UNITS'] ) ? $data['PAYMENT_UNITS'] : config('perfectmoney.units') ) }}">
	@if(isset($data['PAYMENT_ID']))
		<input type="hidden" name="PAYMENT_ID" value="{{ $data['PAYMENT_ID'] }}">
	@endif
	@if( config('perfectmoney.status_url') || isset( $data['STATUS_URL'] ) )
		<input type="hidden" name="STATUS_URL" value="{{ ( isset( $data['STATUS_URL'] ) ? $data['STATUS_URL'] : config('perfectmoney.status_url') ) }}">
	@endif
    <input type="hidden" name="PAYMENT_URL" value="{{ ( isset( $data['PAYMENT_URL'] ) ? $data['PAYMENT_URL'] : config('perfectmoney.payment_url') ) }}">
	@if( config('perfectmoney.payment_url_method') || isset($data['PAYMENT_URL_METHOD']) )
		<input type="hidden" name="PAYMENT_URL_METHOD" value="{{ ( isset( $data['PAYMENT_URL_METHOD'] ) ? $data['PAYMENT_URL_METHOD'] : config('perfectmoney.payment_url_method') ) }}">
	@endif
    <input type="hidden" name="NOPAYMENT_URL" value="{{ ( isset( $data['NOPAYMENT_URL'] ) ? $data['NOPAYMENT_URL'] : config('perfectmoney.nopayment_url') ) }}">
	@if( config('perfectmoney.nopayment_url_method') || isset($data['NOPAYMENT_URL_METHOD']) )
		<input type="hidden" name="NOPAYMENT_URL_METHOD" value="{{ ( isset( $data['NOPAYMENT_URL_METHOD'] ) ? $data['NOPAYMENT_URL_METHOD'] : config('perfectmoney.nopayment_url_method') ) }}">
	@endif
	@if( config('perfectmoney.nopayment_url_method') || isset($data['SUGGESTED_MEMO']) || isset($data['SUGGESTED_MEMO_NOCHANGE']) ) )
		@if( !isset($data['SUGGESTED_MEMO']) && !isset($data['SUGGESTED_MEMO_NOCHANGE']) ))
			<input type="hidden" name="{{ (config('perfectmoney.memo_editable') ? 'SUGGESTED_MEMO' : 'SUGGESTED_MEMO_NOCHANGE') }}" value="{{ config('perfectmoney.suggested_memo') }}">
		@else
			<input type="hidden" name="{{ ( isset( $data['SUGGESTED_MEMO'] ) ? 'SUGGESTED_MEMO' : 'SUGGESTED_MEMO_NOCHANGE' ) }}" value="{{ ( isset( $data['SUGGESTED_MEMO'] ) ? $data['SUGGESTED_MEMO'] : $data['SUGGESTED_MEMO_NOCHANGE'] ) }}">
		@endif
	@endif
    <input type="submit" value="Proceed">
</form>