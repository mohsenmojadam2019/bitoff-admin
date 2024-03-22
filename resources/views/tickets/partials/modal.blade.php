<div class="modal fade" id="replyModal">
    <div class="modal-dialog modal-dialog-centered" role="document" style="min-width:700px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Reply to Ticket
                    <span id="replyModalTicketNumber"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="result"></div>
            <form action="{{route('tickets.store-reply')}}" method="post">
                <div class="modal-footer">

                    {{csrf_field()}}
                    <input type="hidden" name="ticket_id" value="" id="replyTicketId">
                    <textarea name="body" class="form-control d-block" rows="4" placeholder="Enter Reply"></textarea>

                    <label>
                        Close ticket
                        <input type="checkbox" name="close" value="1">
                    </label>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
