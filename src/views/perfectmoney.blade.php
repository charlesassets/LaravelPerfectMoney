<?php
/**
 *
 * @author Charles Patterson <charlesassets@gmail.com>
 *
 */
?>
<form action="https://perfectmoney.is/api/step1.asp" method="POST">
    <input type="hidden" name="PAYEE_ACCOUNT" value="{{ ( isset( $PAYEE_ACCOUNT ) ? $PAYEE_ACCOUNT : config('perfectmoney.marchant_id')) }}">
    <input type="hidden" name="PAYEE_NAME" value="{{ ( isset( $PAYEE_NAME ) ? $PAYEE_NAME : config('perfectmoney.marchant_name') ) }}">
    <input type="text" name="PAYMENT_AMOUNT" value="{{ ( isset( $PAYMENT_AMOUNT ) ? $PAYMENT_AMOUNT : '' ) }}" placeholder="Amount">
    <input type="hidden" name="PAYMENT_UNITS" value="{{ ( isset( $PAYMENT_UNITS ) ? $PAYMENT_UNITS : config('perfectmoney.units') ) }}">
	@if(isset($PAYMENT_ID))
		<input type="hidden" name="PAYMENT_ID" value="{{ $PAYMENT_ID }}">
	@endif
	@if( config('perfectmoney.status_url') || isset( $STATUS_URL ) )
		<input type="hidden" name="STATUS_URL" value="{{ ( isset( $STATUS_URL ) ? $STATUS_URL : config('perfectmoney.status_url') ) }}">
	@endif
    <input type="hidden" name="PAYMENT_URL" value="{{ ( isset( $PAYMENT_URL ) ? $PAYMENT_URL : config('perfectmoney.payment_url') ) }}">
	@if( config('perfectmoney.payment_url_method') || isset($PAYMENT_URL_METHOD) )
		<input type="hidden" name="PAYMENT_URL_METHOD" value="{{ ( isset( $PAYMENT_URL_METHOD ) ? $PAYMENT_URL_METHOD : config('perfectmoney.payment_url_method') ) }}">
	@endif
    <input type="hidden" name="NOPAYMENT_URL" value="{{ ( isset( $NOPAYMENT_URL ) ? $NOPAYMENT_URL : config('perfectmoney.nopayment_url') ) }}">
	@if( config('perfectmoney.nopayment_url_method') || isset($NOPAYMENT_URL_METHOD) )
		<input type="hidden" name="NOPAYMENT_URL_METHOD" value="{{ ( isset( $NOPAYMENT_URL_METHOD ) ? $NOPAYMENT_URL_METHOD : config('perfectmoney.nopayment_url_method') ) }}">
	@endif
	@if( config('perfectmoney.nopayment_url_method') || isset($SUGGESTED_MEMO) || isset($SUGGESTED_MEMO_NOCHANGE) ) )
		@if( !isset($SUGGESTED_MEMO) && !isset($SUGGESTED_MEMO_NOCHANGE) ))
			<input type="hidden" name="{{ (config('perfectmoney.memo_editable') ? 'SUGGESTED_MEMO' : 'SUGGESTED_MEMO_NOCHANGE') }}" value="{{ config('perfectmoney.suggested_memo') }}">
		@else
			<input type="hidden" name="{{ ( isset( $SUGGESTED_MEMO ) ? 'SUGGESTED_MEMO' : 'SUGGESTED_MEMO_NOCHANGE' ) }}" value="{{ ( isset( $SUGGESTED_MEMO ) ? $SUGGESTED_MEMO : $SUGGESTED_MEMO_NOCHANGE ) }}">
		@endif
	@endif
    <input type="submit" value="Proceed">
</form>