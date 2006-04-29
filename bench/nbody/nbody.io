/* The Great Computer Language Shootout
   http://shootout.alioth.debian.org

   Contributed by Ian Osgood */

# similar protocol as Vector (floats), but with Numbers (doubles)
Point := List clone do(
    setSlot("*", method(n, r := Point clone; self foreach(v, r append(v * n)); r) )
    setSlot("/", method(n, r := Point clone; self foreach(v, r append(v / n)); r) )
    square := method( r := Point clone; self foreach(v, r append(v squared)); r)
    # faster in-place operations
    copy := method(p, p foreach(i,v, self atPut(i,v)); self)
    setSlot("*=", method(n, mapInPlace(v, v * n)) )
    setSlot("-=", method(p, mapInPlace(i,v, v - p at(i))) )
    setSlot("+=", method(p, mapInPlace(i,v, v + p at(i))) )
    distanceTo := method(p,
        d := 0
        self foreach(i,v, d = d +(v -(p at(i)) squared) )
        d sqrt
    )
)
vector := method( Point clone appendSeq(thisMessage argsEvaluatedIn(sender)) )

Body := Object clone do(
    solarMass := 4 * Number constants pi squared
    daysPerYear := 365.24
)

bodies := list(
    # Sun
    Body clone do(
    	p := vector(0,0,0)
    	v := vector(0,0,0)
        mass := solarMass
    ),
	# Jupiter
    Body clone do(
        p := vector( 4.84143144246472090e00,
                    -1.16032004402742839e00,
                    -1.03622044471123109e-01)
        v := vector( 1.66007664274403694e-03,
                     7.69901118419740425e-03,
                    -6.90460016972063023e-05) * daysPerYear
        mass :=      9.54791938424326609e-04 * solarMass
    ),
    # Saturn
    Body clone do(
        p := vector( 8.34336671824457987e00,
                     4.12479856412430479e00,
                    -4.03523417114321381e-01)
        v := vector(-2.76742510726862411e-03,  
                     4.99852801234917238e-03,
                     2.30417297573763929e-05) * daysPerYear
        mass :=      2.85885980666130812e-04 * solarMass
    ),
    # Uranus
    Body clone do(
        p := vector( 1.28943695621391310e01,
                    -1.51111514016986312e01,
                    -2.23307578892655734e-01)
        v := vector( 2.96460137564761618e-03,
                     2.37847173959480950e-03,
                    -2.96589568540237556e-05) * daysPerYear
        mass :=      4.36624404335156298e-05 * solarMass
    ),
    # Neptune
    Body clone do(
        p := vector( 1.53796971148509165e01,
                    -2.59193146099879641e01,
                     1.79258772950371181e-01)
        v := vector( 2.68067772490389322e-03,  
                     1.62824170038242295e-03,
                    -9.51592254519715870e-05) * daysPerYear
        mass :=      5.15138902046611451e-05 * solarMass
    )
)

# offset momentum

p := vector(0,0,0)
bodies foreach (body, p -= body v * body mass)
bodies at(0) v = p / Body solarMass

bodies energy := method(
    e := 0
    self foreach (i, body,
        e = e + body mass * body v square sum / 2
        last(size-i-1) foreach (body2,
            e = e - body mass * body2 mass / body p distanceTo(body2 p)
        )
    )
    e
)

bodies advance := method(dt,
    dp := vector(0,0,0); dp2 := vector(0,0,0)
    self foreach(i, body,
        last(size-i-1) foreach (body2,
            dp2 copy(dp copy(body p) -= body2 p)
            mag := dt / body p distanceTo(body2 p) pow(3)
            body  v -= dp  *= (body2 mass * mag)
            body2 v += dp2 *= (body  mass * mag)
        )
    )
    self foreach(body, body p += (dp copy(body v) *= dt) )
)

bodies energy asString(0,9) println

args at(1) asNumber repeatTimes( bodies advance(0.01) )

bodies energy asString(0,9) println
