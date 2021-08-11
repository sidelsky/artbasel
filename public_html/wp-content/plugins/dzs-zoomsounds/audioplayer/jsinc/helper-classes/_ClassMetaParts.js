

export class _ClassMetaParts{
  constructor(selfClass) {
    this.selfClass = selfClass;
  }

  set_extraHtmlFloatRight(extraHtml){


    this.selfClass.cthis.find('.extrahtml-in-float-right').eq(0).html(extraHtml);
  }

}
