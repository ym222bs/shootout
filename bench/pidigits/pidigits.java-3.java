/**
 * The Computer Language Benchmarks Game
 * http://shootout.alioth.debian.org/

   contributed by Mike Pall
   java port by Stefan Krause
   Data Parallel adaptation by Sassa NF
*/

import java.util.concurrent.*;

public class pidigits {
   final static int S = 0, BS = 1, BQ = 2, BR = 3, BT = 4, // plain int values
                    // GmpIntegers
                    U = 5,
                    ER1 = 6, ET1 = 7, EQ1 = 8, Q1 = 9, R1 = 10, S1 = 11,
                    T1 = 12, U1 = 13, V1 = 14, V = 15,
                    ER = 16, ET = 17, EU = 18, EV = 19,
                    ES1 = 20, 
                    Q = 21, R = 22, T = 23; // these are always available

   long [] values = new long[ 24 ];
   Semaphore [] sema = new Semaphore[ values.length ];
   Semaphore allDone = new Semaphore( 0 );
   final static int ADD = 0, MUL = 1, DIV = 2;

   ExecutorService executor = Executors.newFixedThreadPool( 3 );

   int i, k, c;
   int digit;
   int d;
   StringBuilder strBuf = new StringBuilder(20);
   final int n;

   private pidigits(int n)
   {
      this.n=n;
   }

   public class exec implements Runnable
   {
     Runnable [] seq_tasks;
     int instr, dest, op1, op2;

     public exec( Runnable[] tasks )
     {
       seq_tasks = tasks;
     }

     public exec( int ins, int d, int o1, int o2 )
     {
       instr = ins; dest = d; op1 = o1; op2 = o2;
     }

     public void run()
     {
       if ( seq_tasks != null )
       {
         for( Runnable r: seq_tasks ) r.run();
         allDone.release();
         return;
       }

       try
       {
         sema[ op1 ].acquire(); sema[ op1 ].release();
         sema[ op2 ].acquire(); sema[ op2 ].release();
       }
       catch( Exception e ){}

       if ( instr == MUL )
       {
         GmpUtil.mul( values[ dest ], values[ op1 ], (int)values[ op2 ] );
       }
       else if ( instr == ADD )
       {
         GmpUtil.add( values[ dest ], values[ op1 ], values[ op2 ] );
       }
       else
         GmpUtil.div( values[ dest ], values[ op1 ], values[ op2 ] );

       sema[ dest ].release();
     }
   };

   // compose_r = ( q,r; s,t ) = ( bq, br; bs, bt ) x (q,r; s,t)
   // bs == 0, hence s == 0 and multiplications involving bs and s aren't here (br*s, bt*s)
   // bt == 1 hence multiplications involving bt aren't here (s*bt, t*bt)

   // compose_l = ( q,r; s,t ) = (q,r; s,t) x ( bq, br; bs, bt )
   // extract = ( q*3 + r )/( s*3 + t ) compared to ( q*4 + r )/( s*4 + t )
   final Runnable[] COMPOSE_R = new Runnable[]{ 
                         new exec( new Runnable[]{ new exec( MUL, V, T, BR ),
                                                   new exec( MUL, Q1, Q, BQ )} ),
                         new exec( new Runnable[]{ new exec( MUL, R1, R, BQ ),
                                                   new exec( ADD, R1, R1, V ) } ) };

   final Runnable[] COMPOSE_L = new Runnable[]{ 
                         new exec( new Runnable[]{ new exec( MUL, U, Q, BR ),
                                                   new exec( MUL, R1, R, BT ),
                                                   new exec( ADD, R1, R1, U ) } ),
                         new exec( new Runnable[]{ new exec( MUL, T1, T, BT ),
                                                   new exec( MUL, Q1, Q, BQ ) } ),
                         // digit extraction logic here
                         new exec( new Runnable[]{ new exec( MUL, U1, Q, BS ), // BS == 3 here
                                                   new exec( ADD, EU, U1, R ),
                                                   new exec( DIV, ER, EU, T ) } ),

                         new exec( new Runnable[]{ new exec( MUL, ER1, Q, S ), // S == 4
                                                   new exec( ADD, ES1, ER1, R ),
                                                   new exec( DIV, ET, ES1, T ) } )
                                              };


   private void multi_threaded_compute( Runnable[] code, int bq, int br, int bt )
   {
     allDone.drainPermits();

     for( int i = BQ; i < Q; ++i ) sema[ i ].drainPermits();

     values[ BQ ] = bq;
     sema[ BQ ].release();
     values[ BR ] = br;
     sema[ BR ].release();
     values[ BT ] = bt;
     sema[ BT ].release();

     for( int i = 1; i < code.length; ++i ) executor.execute( code[ i ] ); // we are one thread, so skip code[ 0 ]

     code[ 0 ].run();
     try
     {
       allDone.acquire( code.length );
     }
     catch( Exception e ){}
   }

   /* Print one digit. Returns 1 for the last digit. */
   private boolean prdigit(int y, boolean isWarm)
   {
      strBuf.append(y);
      if (++i % 10 == 0 || i == n) {
         if (i%10!=0) for (int j=10-(i%10);j>0;j--) { strBuf.append(" "); }
         strBuf.append("\t:");
         strBuf.append(i);
        if (isWarm) System.out.println(strBuf);
        strBuf.setLength( 0 ); // clear the contents
      }
      return i == n;
   }

   /* Generate successive digits of PI. */
   void pidigits(boolean isWarm)
   {
     int k = 1;
     d = 0;
     i = 0;
     for( int i = U; i < values.length; ++i ) values[ i ] = GmpUtil.init();

     GmpUtil.set( values[ Q ], 1 );
     GmpUtil.set( values[ T ], 1 );
     GmpUtil.set( values[ R ], 0 );
     values[ BS ] = 3;
     values[ S ] = 4;
     for( int i = 0; i < sema.length; ++i ) sema[ i ] = new Semaphore( 0 ); // these are initially unavailable
     sema[ Q ].release(); // these are always avalable
     sema[ R ].release();
     sema[ S ].release();
     sema[ BS ].release();
     sema[ T ].release();
     for (;;) {
       multi_threaded_compute( COMPOSE_L, k, 4*k+2, 2*k+1 );
       int y = GmpUtil.intValue( values[ ER ] );
       if (y == GmpUtil.intValue( values[ ET ] )) {
         if (prdigit(y,isWarm)) {
           for( int i = Q; i < values.length; ++i ) GmpUtil.clear( values[ i ] );
           return;
         }
         multi_threaded_compute( COMPOSE_R, 10, -10*y, 1 );
       } else {
         long g = values[ T ];
         values[ T ] = values[ T1 ];
         values[ T1 ] = g; // to save on init/GC costs
         k++;
       }
       long g = values[ Q ];
       values[ Q ] = values[ Q1 ];
       values[ Q1 ] = g;
       g = values[ R ];
       values[ R ] = values[ R1 ];
       values[ R1 ] = g;
     }
   }

   public static void main(String[] args){
      pidigits m = new pidigits(Integer.parseInt(args[0]));
      //for (int i=0; i<19; ++i) m.pidigits(false);
      m.pidigits(true);

      System.exit(0);
   }
}

class GmpUtil {
   static GmpInteger g = new GmpInteger();

   // this is a silly workaround to reuse the same jgmplib; it would be better that mpz_init() returned long
   // and _all_ native methods were protected or public static.
   public synchronized static long init(){ g.mpz_init(); return g.pointer; }

   public synchronized static void clear(long pointer){ g.mpz_clear(pointer); }

   public static void set(long pointer, int value) { GmpInteger.mpz_set_si(pointer, value); }

   public static void mul(long pointer, long src, int val) { GmpInteger.mpz_mul_si(pointer, src, val); }

   public static void add(long pointer, long op1, long op2) { GmpInteger.mpz_add(pointer, op1, op2); }

   public static void div(long pointer, long op1, long op2) { GmpInteger.mpz_tdiv_q(pointer, op1, op2); }

   public static int intValue( long pointer ) { return GmpInteger.mpz_get_si(pointer); }
}

class GmpInteger {
   // same class as in another Java submission, but with different access modifiers to enable GmpUtil

   // Public methods

   public GmpInteger() {
      mpz_init();
   }

   public GmpInteger(int value) {
      this();
      mpz_set_si(pointer, value);
   }

   public void set(int value) { mpz_set_si(pointer, value); }

   public void mul(GmpInteger src, int val) { mpz_mul_si(pointer, src.pointer, val); }

   public void add(GmpInteger op1, GmpInteger op2) { mpz_add(pointer, op1.pointer, op2.pointer); }

   public void div(GmpInteger op1, GmpInteger op2) { mpz_tdiv_q(pointer, op1.pointer, op2.pointer); }

   public int intValue() { return mpz_get_si(pointer); }

   public double doubleValue() { return mpz_get_d(pointer); }

   // Non public stuff

   static {
      System.loadLibrary("jgmplib");
   }
   long pointer;

   protected void finalize()  {
      mpz_clear(pointer);
   }

   native void mpz_init();

   native void mpz_clear(long src);

   static native void mpz_mul_si(long dest, long src,
         int val);

   static native void mpz_add(long dest, long src,
         long src2);

   static native void mpz_tdiv_q(long dest, long src,
         long src2);

   static native void mpz_set_si(long src, int value);

   static native int mpz_get_si(long src);

   static native double mpz_get_d(long src);
}

