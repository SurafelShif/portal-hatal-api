<?php

namespace App\Messages;

class ResponseMessages
{
    const SUCCESS_ACTION = 'הפעולה התבצעה בהצלחה';
    const ERROR_OCCURRED = 'אירעה שגיאה';
    const NO_ROLE_ASSIGNED = 'לא הוקצה תפקיד למשתמש';
    const UNAUTHENTICATED = 'המשתמש לא מחובר';
    const USER_NOT_FOUND = 'המשתמש לא נמצא';
    const WEBSITE_NOT_FOUND = 'אתר לא נמצא';
    const MISSING_ROLE = 'לא נמצא תפקיד';
    const NOT_ADMIN = 'המשתמש אינו מנהל מערכת';
    const NOT_USER = 'המשתמש הינו מנהל מערכת';
    const INVALID_REQUEST = 'בקשה לא תקינה';
    const SELF_REMOVAL = 'לא ניתן להסיר הרשאה לעצמך';
    const SUCCESS_NO_ACTION_NEEDED = 'לא ניתן לבצע את הפעולה';
    const FORBIDDEN = 'אין לך הרשאות כדי לבצע פעולה זו';
}
