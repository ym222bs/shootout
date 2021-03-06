(* The Great Computer Language Shootout
   http://shootout.alioth.debian.org

   contributed by Isaac Gouy (Oberon-2 novice)
*)

MODULE nsieve;
IMPORT Shootout,Out;

TYPE
   FlagsType = ARRAY OF BOOLEAN;

VAR
   n, m: LONGINT;
   flags: POINTER TO FlagsType;


PROCEDURE NSieve(m: LONGINT; VAR isPrime: FlagsType): LONGINT;
VAR
   count, i, k: LONGINT;
BEGIN
   FOR i := 2 TO m DO isPrime[i] := TRUE; END;

   count := 0;
   FOR i := 2 TO m DO
      IF isPrime[i] THEN
	 INC(count);
         k := i+i;
         WHILE k <= m DO
            isPrime[k] := FALSE;
	    INC(k, i);
         END;
      END;
   END;
   RETURN count;
END NSieve;

BEGIN
   n := Shootout.Argi();
   IF n < 2 THEN n := 2; END;                    
    
   m := 10000 * ASH(1,n);
   NEW(flags, m+1);
   Out.String("Primes up to "); Out.Int(m,8); Out.String(" "); Out.Int(NSieve(m,flags^),8); Out.Ln;
    
   m := 10000 * ASH(1,n-1);
   Out.String("Primes up to "); Out.Int(m,8); Out.String(" "); Out.Int(NSieve(m,flags^),8); Out.Ln;
    
   m := 10000 * ASH(1,n-2);
   Out.String("Primes up to "); Out.Int(m,8); Out.String(" "); Out.Int(NSieve(m,flags^),8); Out.Ln;         
END nsieve.